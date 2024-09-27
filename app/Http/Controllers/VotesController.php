<?php

namespace App\Http\Controllers;
use App\Models\Vote;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Carbon; // For handling time and token expiration


use App\Models\Recipe;

class VotesController extends Controller
{
    public function vote(Request $request, $recipeId)
{
    // Find the user
    $user = User::find($request->user_id);

    // Check if the user is authorized to vote (role must be 'user')
    if ($user->role !== 'user') {
        return response()->json(['error' => 'You are not authorized to vote.'], 403);
    }

    // Find the recipe
    $recipe = Recipe::find($recipeId);
    if (!$recipe) {
        return response()->json(['error' => 'Recipe not found.'], 404);
    }

    // Check if the recipe is approved
    if ($recipe->status !== 'approved') {
        return response()->json(['error' => 'You cannot vote for a recipe that is not approved.'], 400);
    }

    // Get the topic associated with the recipe
    $topic = $recipe->topic;

    // Check if voting is allowed (10 days after topic's end date)
    $currentDate = Carbon::now();
    $votingStartDate = Carbon::parse($topic->end_date);
    $votingEndDate = $votingStartDate->addDays(10);

    if ($currentDate->lt($votingStartDate)) {
        return response()->json(['error' => 'Voting has not started yet.'], 400);
    }

    if ($currentDate->gt($votingEndDate)) {
        return response()->json(['error' => 'Voting period is over.'], 400);
    }

    // Check if the user has already voted for any recipe in this topic
    $existingVoteForAnyRecipe = Vote::where('user_id', $user->id)
        ->whereHas('recipe', function($query) use ($topic) {
            $query->where('topic_id', $topic->id);
        })->exists();

    if ($existingVoteForAnyRecipe) {
        return response()->json(['error' => 'You have already voted for a recipe in this topic.'], 400);
    }

    // Check if the user has already voted for this specific recipe
    $existingVote = Vote::where('user_id', $user->id)
        ->where('recipe_id', $recipeId)
        ->first();

    if ($existingVote) {
        return response()->json(['error' => 'You have already voted for this recipe.'], 400);
    }

    // Create a new vote
    $vote = Vote::create([
        'user_id' => $user->id,
        'recipe_id' => $recipeId,
    ]);

    // Increment the recipe's vote count
    $recipe->increment('vote');

    return response()->json(['message' => 'Vote successfully recorded.'], 200);
}


}
