<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $questions = Question::oldest('created_at')->get();
        $answeredQuestions = Question::where('is_answered', true)->orderBy('created_at', 'asc')->get();
        $unAnsweredQuestions = Question::where('is_answered', false)->orderBy('created_at', 'asc')->get();

        $visitorId = $request->cookie('visitor_id');
        $userQuestions = Question::where('visitor_id', $visitorId)->get();
        $answeredUserQuestions = Question::where('visitor_id', $visitorId)
            ->where('is_answered', true)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('index', compact('questions', 'answeredQuestions', 'unAnsweredQuestions', 'userQuestions', 'answeredUserQuestions'));
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
            'content' => 'required|string|max:1000',
        ]);
        $visitorId = $request->cookie('visitor_id');

        Question::create($fields + ['visitor_id' => $visitorId]);

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
        $answeredQuestions = Question::where('is_answered', true)->orderBy('created_at', 'asc')->get();

        $user = Auth::user();

        return response()->json([
            'questions' => $questions,
            'user' => $user,
            'answeredQuestions' => $answeredQuestions
        ]);
    }


    public function userQuestions(Request $request)
    {
        $visitorId = request()->cookie('visitor_id');

        $visitorId = $request->cookie('visitor_id');
        $questions = Question::where('visitor_id', $visitorId)->get();

        return view('questions.my', compact('questions'));
    }



    public function visitorSearch(Request $request)
    {
        $search = $request->search;

        $visitorId = $request->cookie('visitor_id');
        $userQuestions = Question::where('visitor_id', $visitorId)
            ->where(function ($query) use ($search) {
                $query->where('content', 'LIKE', "%{$search}%")
                    ->orWhere('answer', 'LIKE', "%{$search}%");
            })
            ->get();

        $answeredUserQuestions = Question::where('visitor_id', $visitorId)
            ->where('is_answered', true)
            ->orderBy('created_at', 'asc')
            ->get();



        $user = Auth::user();

        return response()->json([
            'questions' => $userQuestions,
            'user' => $user,
            'answeredQuestions' => $answeredUserQuestions
        ]);
    }
}
