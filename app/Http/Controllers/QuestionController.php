<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::all();
        return view('index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'content' => 'required|string|max:800',
        ]);

        Question::create($fields);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'تم إرسال السؤال بنجاح!']);
        }

        return redirect()->back()->with('success', 'Question posted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $fields = $request->validate([
            'answer' => 'required|string|max:800',

        ]);

        $question->update($fields + ['is_answered' => true]);

        return redirect()->back()->with('success', 'Answer posted successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->back()->with('success', 'Question deleted successfully.');
    }



    public function search(Request $request)
    {
        $search = $request->search;
        $questions = Question::where('content', 'LIKE', "%{$search}%")
            ->orWhere('answer', 'LIKE', "%{$search}%")
            ->get();


        return response()->json($questions);
    }
}
