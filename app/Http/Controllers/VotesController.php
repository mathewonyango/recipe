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
    public function vote(Request $request)
{

    $apiKey = $request->input('api_key'); // Use input() to get data from the body
    $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

    // Check if the provided API key matches the expected API key
    if ($apiKey !== $expectedApiKey) {
        return response()->json(['response_description' => 'Unauthorized access. Invalid API Key.'], 401);
    }
    // Find the user
    $user = User::find($request->user_id);

    // Check if the user is authorized to vote (role must be 'user')
    if ($user->role !== 'user') {
        return response()->json(['response_description' => 'Chefs are Not Authorized to Vote!.'], 403);
    }

    // Find the recipe
    $recipe = Recipe::find($request->recipe_id);
    if (!$recipe) {
        return response()->json(['response_description' => 'Recipe not found.'], 404);
    }

    // Check if the recipe is approved
    if ($recipe->status !== 'approved') {
        return response()->json(['response_description' => 'You cannot vote for a recipe that is not approved.'], 400);
    }

    // Get the topic associated with the recipe
    $topic = $recipe->topic;

   // Check if voting is allowed (10 days after topic's end date)
$currentDate = Carbon::now();
$votingStartDate = Carbon::parse($topic->end_date);
$votingEndDate = $votingStartDate->copy()->addDays(10); // Extend voting to 10 days after end_date

if ($currentDate->lt($votingStartDate)) {
    return response()->json(['response_description' => 'Voting has not started yet.'], 400);
}

if ($currentDate->gt($votingEndDate)) {
    return response()->json(['response_description' => 'Voting period is over.'], 400);
}

    // Check if the user has already voted for any recipe in this topic
    // $existingVoteForAnyRecipe = Vote::where('user_id', $user->id)
    //     ->whereHas('recipe', function($query) use ($topic) {
    //         $query->where('topic_id', $topic->id);
    //     })->exists();

    // if ($existingVoteForAnyRecipe) {
    //     return response()->json(['response_description' => 'You have already voted for a recipe in this topic.'], 400);
    // }

    // Check if the user has already voted for this specific recipe
    $existingVote = Vote::where('user_id', $user->id)
        ->where('recipe_id', $request->recipe_id)
        ->first();

    if ($existingVote) {
        return response()->json(['response_description' => 'You have already voted for this recipe.'], 400);
    }

    // Create a new vote
    $vote = Vote::create([
        'user_id' => $user->id,
        'recipe_id' => $request->recipe_id,
    ]);

    // Increment the recipe's vote count
    $recipe->increment('vote');

    return response()->json(['response_description' => 'Vote successfully recorded.'], 200);
}


}
