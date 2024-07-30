<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Poll $poll)
    {
        $questions = $poll->questions()->paginate(10);
        return view('dashboard.questions.index', compact('questions', 'poll'));
    }

    public function store(Request $request, Poll $poll)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $poll->questions()->create([
            'question' => $request->question,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Question created successfully.');
    }

    public function update(Request $request, Poll $poll, Question $question)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $question->update([
            'question' => $request->question,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Question updated successfully.');
    }

    public function destroy(Poll $poll, Question $question)
    {
        $question->delete();
        return redirect()->back()->with('success', 'Question deleted successfully.');
    }
}
