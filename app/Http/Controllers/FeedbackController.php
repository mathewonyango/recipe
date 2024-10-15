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

        if ($feedback) {
            return response()->json([
                'response_description' => 'feedback submitted successfully',
                'response' => '000', 'message' => 'Feedback submitted successfully', 'feedback' => $feedback], 201);
        } else {
            return response()->json([
                'response_description' => 'feedback submission failed',
                'response' => '999', 'message' => 'An error occurred. Please try again.'], 422);
        }
    }

    public function getUserFeedback($userId)
    {
        $feedback = Feedback::where('user_id', $userId)->get();
        return response()->json(['feedback' => $feedback]);
    }

    public function getAllFeedback()
    {
        $feedback = Feedback::with('user')->get();
        if ($feedback) {
            return response()->json([
                'response_description' => 'feedback fetched successfully',
                'response' => '000', 'feedback' => $feedback], 200);
        } else {
            return response()->json([
                'response_description' => 'feedback not found',
                'response' => '999', 'message' => 'An error occurred. Please try again.'], 422);
        }
    }
}
