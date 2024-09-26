<?php

namespace App\Http\Controllers;
use App\Models\Vote;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\Recipe;

class VotesController extends Controller
{
    public function vote(Request $request, $recipeId)
    {
        $user =User::find($request->user_id);

        // Check if the user is a voter
        if ($user->role !== 'user') {
            return response()->json(['error' => 'You are not authorized to vote.'], 403);
        }

        // Check if the recipe exists
        $recipe = Recipe::find($recipeId);
        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found.'], 404);
        }

        // Check if the user has already voted for any recipe
        $existingVoteForAnyRecipe = Vote::where('user_id', $user->id)->exists();
        if ($existingVoteForAnyRecipe) {
            return response()->json(['error' => 'You have already voted for a recipe. You cannot vote again.'], 400);
        }

        // Check if the user has already voted for this specific recipe
        $existingVote = Vote::where('user_id', $user->id)->where('recipe_id', $recipeId)->first();
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
