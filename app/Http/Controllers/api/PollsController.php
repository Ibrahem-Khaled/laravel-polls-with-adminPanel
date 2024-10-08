<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Poll;
use Illuminate\Http\Request;

class PollsController extends Controller
{
    public function polls()
    {
        $user = auth()->guard('api')->user();

        // جلب جميع الاستطلاعات مع الأسئلة والخيارات
        $polls = Poll::with(['questions.options'])->get();

        // جلب معرفات الخيارات التي أجاب عليها المستخدم
        $answeredOptionIds = $user->answers()->pluck('option_id')->toArray();

        // جلب معرفات الأسئلة التي أجاب عليها المستخدم
        $answeredQuestionIds = Option::whereIn('id', $answeredOptionIds)
            ->pluck('question_id')
            ->unique()
            ->toArray();

        // تصفية الاستطلاعات التي لم يكمل المستخدم الإجابة على جميع أسئلتها
        $unansweredPolls = $polls->filter(function ($poll) use ($answeredQuestionIds) {
            $pollQuestionIds = $poll->questions->pluck('id')->toArray(); // جلب جميع معرفات الأسئلة في الاستطلاع

            // مقارنة عدد الأسئلة التي أجاب عليها المستخدم مع عدد الأسئلة في الاستطلاع
            $answeredInPoll = array_intersect($pollQuestionIds, $answeredQuestionIds); // الأسئلة التي أجاب عليها المستخدم في هذا الاستطلاع

            // إذا لم تكن جميع الأسئلة قد تمت الإجابة عليها، نرجع الاستطلاع
            return count($answeredInPoll) < count($pollQuestionIds);
        });

        // إرجاع الاستطلاعات غير المكتملة
        return response()->json($unansweredPolls, 200);
    }


    public function completePolls()
    {
        $user = auth()->guard('api')->user();

        // تحميل الاستطلاعات مع الأسئلة والخيارات
        $polls = Poll::with('questions.options')->get();

        // التحقق من أن هناك استطلاعات
        if ($polls->isEmpty()) {
            return response()->json(['error' => 'No polls found.'], 404);
        }

        // فلترة الاستطلاعات المكتملة
        $completePolls = $polls->filter(function ($poll) use ($user) {
            $questions = $poll->questions;
            $questionCount = $questions->count();

            // جلب معرفات الإجابات للمستخدم
            $answeredOptionIds = $user->answers()->pluck('option_id')->toArray();

            // التحقق من معرفات الأسئلة التي تمت الإجابة عليها
            $answeredQuestionIds = Option::whereIn('id', $answeredOptionIds)
                ->whereIn('question_id', $questions->pluck('id')->toArray())
                ->pluck('question_id')
                ->unique()
                ->toArray();

            // التحقق من عدد الأسئلة التي تمت الإجابة عليها
            return count($answeredQuestionIds) === $questionCount;
        });

        // التحقق من وجود استطلاعات مكتملة
        if ($completePolls->isEmpty()) {
            return response()->json(['message' => 'No completed polls found.'], 200);
        }

        // تحويل الاستطلاعات المكتملة إلى مصفوفة JSON
        $completePollsArray = $completePolls->map(function ($poll) {
            return [
                'id' => $poll->id,
                'title' => $poll->title,
                'description' => $poll->description,
                'image' => $poll->image,
                'price' => $poll->price,
                'status' => $poll->status,
                'visibility' => $poll->visibility,
                'questions' => $poll->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'text' => $question->text,
                        'options' => $question->options->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'text' => $option->text,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json($completePollsArray, 200);
    }


    public function poll($pollId)
    {
        $user = auth()->guard('api')->user();
        $poll = Poll::with(['questions.options'])->find($pollId);

        if (!$poll) {
            return response()->json(['error' => 'Poll not found.'], 404);
        }

        $userAnswers = $user->answers()
            ->whereIn('option_id', $poll->questions->pluck('options.id')->toArray())
            ->get();

        $questionsWithOptionsAndAnswers = $poll->questions->map(function ($question) use ($userAnswers) {
            $userAnswer = $userAnswers->firstWhere('option_id', $question->options->pluck('id')->toArray());

            return [
                'id' => $question->id,
                'question' => $question->question,
                'description' => $question->description,
                'options' => $question->options->map(function ($option) use ($userAnswer) {
                    return [
                        'id' => $option->id,
                        'option_text' => $option->option,
                        'is_selected' => $userAnswer ? $userAnswer->option_id === $option->id : false,
                    ];
                }),
                'answered' => $userAnswer ? true : false
            ];
        });

        return response()->json([
            'poll' => $poll->title,
            'questions' => $questionsWithOptionsAndAnswers,
        ], 200);
    }

    public function pollWithUserAnswers($pollId)
    {
        $user = auth()->guard('api')->user();
        $poll = Poll::with(['questions.options'])->find($pollId);

        if (!$poll) {
            return response()->json(['error' => 'الاستطلاع غير موجود.'], 404);
        }

        $userAnswers = $user->answers()
            ->whereIn('option_id', $poll->questions->pluck('options.*.id')->flatten()->toArray())
            ->get();

        $questionsWithUserAnswers = $poll->questions->map(function ($question) use ($userAnswers) {
            $userAnswer = $userAnswers->firstWhere('question_id', $question->id);

            return $userAnswer ? [
                'id' => $question->id,
                'question' => $question->question,
                'user_answer' => [
                    'option_id' => $userAnswer->id,
                    'option_text' => $userAnswer->option,
                ]
            ] : null;
        })->filter()->values(); // إزالة الأسئلة التي لم يتم الإجابة عليها وإعادة الفهرسة

        return response()->json([
            'poll' => $poll->title,
            'questions' => $questionsWithUserAnswers,
        ], 200);
    }

    public function setUserAnswers(Request $request, $pollId)
    {
        $user = auth()->guard('api')->user();
        $poll = Poll::find($pollId);

        // التحقق من وجود الاستطلاع
        if (!$poll) {
            return response()->json(['error' => 'Poll not found.'], 404);
        }

        $answer = $request->input('answer');
        $option = Option::find($answer);

        // التحقق من صحة الخيار وأنه متعلق بالاستطلاع
        if (!$option || $option->question->poll_id != $pollId) {
            return response()->json(['error' => 'Invalid option or poll ID.'], 400);
        }

        // التحقق من وجود إجابة سابقة
        $existingAnswer = $user->answers()->where('option_id', $option->id)->first();

        // إذا كانت هناك إجابة سابقة، نقوم بتحديثها
        if ($existingAnswer) {
            // فك ارتباط الإجابة القديمة وربط الإجابة الجديدة
            $user->answers()->detach($existingAnswer->id); // فك ارتباط الإجابة القديمة
            $user->answers()->attach($answer); // ربط الإجابة الجديدة

            return response()->json(['message' => 'Answer updated successfully.'], 200);
        }

        // إذا لم تكن هناك إجابة سابقة، نقوم بحفظ الإجابة الجديدة
        $user->answers()->attach($answer);

        return response()->json(['message' => 'Answer saved successfully.'], 200);
    }


    public function checkUserAnswered($pollId)
    {
        $user = auth()->guard('api')->user();
        $poll = Poll::find($pollId);
        if (!$poll) {
            return response()->json(['error' => 'Poll not found.'], 404);
        }
        $questions = $poll->questions;
        $questionCount = $questions->count();

        $answeredOptionIds = $user->answers()->pluck('option_id')->toArray();

        $answeredQuestionIds = Option::whereIn('id', $answeredOptionIds)
            ->whereIn('question_id', $questions->pluck('id')->toArray())
            ->pluck('question_id')
            ->unique()
            ->toArray();

        if (count($answeredQuestionIds) === $questionCount) {
            $pollCredit = $poll->price;
            $user->balance += $pollCredit;
            $user->save();

            return response()->json(['message' => 'All questions answered. Balance updated.', 'balance' => $user->balance], 200);
        }
        return response()->json(['message' => 'Not all questions answered.'], 200);
    }


}
