<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Question;


class OptionController extends Controller
{
    public function index(Question $question)
    {
        $options = $question->options()->paginate(10);
        return view('dashboard.options.index', compact('options', 'question'));
    }

    public function store(Request $request, Question $question)
    {
        $request->validate([
            'option' => 'required|string|max:255',
        ]);

        $question->options()->create([
            'option' => $request->option,
        ]);

        return redirect()->route('questions.options.index', $question)->with('success', 'Option created successfully.');
    }

    public function update(Request $request, Question $question, Option $option)
    {
        $request->validate([
            'option' => 'required|string|max:255',
        ]);

        $option->update([
            'option' => $request->option,
        ]);

        return redirect()->route('questions.options.index', $question)->with('success', 'Option updated successfully.');
    }

    public function destroy(Question $question, Option $option)
    {
        $option->delete();
        return redirect()->route('questions.options.index', $question)->with('success', 'Option deleted successfully.');
    }
}
