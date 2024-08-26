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
        $polls = Poll::with(['questions.options'])->get();

        $answeredOptionIds = $user->answers()->pluck('option_id')->toArray();

        $answeredPollIds = Option::whereIn('id', $answeredOptionIds)
            ->with('question.poll')
            ->get()
            ->pluck('question.poll.id')
            ->unique()
            ->toArray();

        $completedPollIds = array_filter($answeredPollIds, function ($pollId) use ($answeredOptionIds) {
            $pollQuestionsCount = Poll::find($pollId)->questions()->count();
            $answeredQuestionsCount = Option::whereIn('id', $answeredOptionIds)
                ->whereHas('question', function ($query) use ($pollId) {
                    $query->where('poll_id', $pollId);
                })
                ->count();

            return $pollQuestionsCount === $answeredQuestionsCount;
        });

        $unansweredPolls = $polls->filter(function ($poll) use ($completedPollIds) {
            return !in_array($poll->id, $completedPollIds);
        });

        return response()->json($unansweredPolls, 200);
    }

    public function completePolls()
    {
        $user = auth()->guard('api')->user();
        $polls = Poll::with('questions.options')->get();
        $completePolls = $polls->filter(function ($poll) use ($user) {
            $questions = $poll->questions;
            $questionCount = $questions->count();
            $answeredOptionIds = $user->answers()->pluck('option_id')->toArray();

            $answeredQuestionIds = Option::whereIn('id', $answeredOptionIds)
                ->whereIn('question_id', $questions->pluck('id')->toArray())
                ->pluck('question_id')
                ->unique()
                ->toArray();

            return count($answeredQuestionIds) === $questionCount;
        });

        return response()->json($completePolls, 200);
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
        if (!$poll) {
            return response()->json(['error' => 'Poll not found.'], 404);
        }

        $answer = $request->input('answer');
        $option = Option::find($answer);
        if (!$option || $option->question->poll_id != $pollId) {
            return response()->json(['error' => 'Invalid option or poll ID.'], 400);
        }
        $existingAnswer = $user->answers()->where('option_id', $answer)->first();
        if ($existingAnswer) {
            return response()->json(['error' => 'You cannot answer the same question twice.'], 400);
        }
        $user->answers()->attach($answer);

        return response()->json(['message' => 'Answers saved successfully.'], 200);
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
