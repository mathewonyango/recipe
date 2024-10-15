<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;



class FeedbackController extends Controller
{
    //

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'text' => 'required|string',
        ]);

        $feedback = Feedback::create($validatedData);

        return response()->json(['message' => 'Feedback submitted successfully', 'feedback' => $feedback], 201);
    }

    public function getUserFeedback($userId)
    {
        $feedback = Feedback::where('user_id', $userId)->get();
        return response()->json(['feedback' => $feedback]);
    }

    public function getAllFeedback()
    {
        $feedback = Feedback::with('user')->get();
        return response()->json(['feedback' => $feedback]);
    }
}
