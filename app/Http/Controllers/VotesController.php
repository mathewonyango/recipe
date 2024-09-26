<?php

namespace App\Http\Controllers;
use App\Models\Vote;

use Illuminate\Http\Request;

class VotesController extends Controller
{
    public function vote(Request $request, $recipeId)
{
    $userId = $request->user_id; // Get the authenticated user's ID

    // Check if the user has already voted for this recipe
    if (Vote::where('recipe_id', $recipeId)->where('user_id', $userId)->exists()) {
        return response()->json(['message' => 'You have already voted for this recipe.'], 400);
    }

    // Create a new vote
    Vote::create([
        'recipe_id' => $recipeId,
        'user_id' => $userId,
    ]);

    return response()->json(['message' => 'Vote recorded successfully!']);
}
}
