<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
//rating
use App\Models\Rating;
//View
use App\Models\View;

// Topic
use App\Models\Topic;
//comment
use App\Models\Comment;

class RecipesController extends Controller
{


    public function index()
    {
        // Fetch recipes with their related topic, chef, and vote count
        $recipes = Recipe::with(['topic', 'chef'])
            ->withCount('votes') // Count the number of votes for each recipe
            ->orderBy('votes_count', 'desc') // Order by the vote count in descending order
            ->paginate(10); // Paginate the results

        return view('Recipe.index', compact('recipes'));
    }




    public function submitRecipe(Request $request)
{

          // Check for API key
          $apiKey = $request->input('api_key'); // Use input() to get data from the body
          $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

          // Check if the provided API key matches the expected API key
          if ($apiKey !== $expectedApiKey) {
              return response()->json([
                'response'=>"401",
                'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
          }

    // Validation rules
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'topic_id' => 'required|integer|exists:topics,id',
        'servings' => 'required|integer',
        'prep_time' => 'required|integer',
        'cook_time' => 'required|integer',
        'total_time' => 'required|integer',
        'ingredients' => 'required|string',
        'instructions' => 'required|string',
        'chef_id' => 'required|integer',
        'image' => 'nullable|string', // Image validation
        'tags' => 'nullable|string', // Tags should be a string
        'difficulty_level' => 'required|string|in:easy,medium,hard', // Difficulty level validation
        'nutritional_information' => 'nullable|string', // Optional nutritional info
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        // Return a custom error response
        return response()->json([
            'response' => "999",
            'response_description' => 'Please fill all the required fields.',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Create a new recipe with the 'draft' status by default
    $recipe = Recipe::create([
        'title' => $request->title,
        'topic_id' => $request->topic_id,
        'servings' => $request->servings,
        'prep_time' => $request->prep_time,
        'cook_time' => $request->cook_time,
        'total_time' => $request->total_time,
        'ingredients' => $request->ingredients,
        'instructions' => $request->instructions,
        'user_id' => $request->chef_id, // Assumes user is authenticated
        'status' => 'draft', // default status
        'image' =>$request->image, // Store image
        'tags' => $request->tags,
        'difficulty_level' => $request->difficulty_level,
        'nutritional_information' => $request->nutritional_information,
    ]);

   // Fetch the topic title using the topic_id
   $topic = Topic::find($request->topic_id);

   // Return success response with topic title
   return response()->json([
       'response' => "000",
       'response_description' => 'Recipe submitted successfully!',
       'recipe' => [
        'recipe_id'=>$recipe->id,
           'title' => $recipe->title,
           'topic_title' => $topic->name, // Return the topic title instead of ID
           'servings' => $recipe->servings,
           'prep_time' => $recipe->prep_time,
           'cook_time' => $recipe->cook_time,
           'total_time' => $recipe->total_time,
           'ingredients' => $recipe->ingredients,
           'instructions' => $recipe->instructions,
           'image' => $recipe->image,
           'tags' => $recipe->tags,
           'difficulty_level' => $recipe->difficulty_level,
           'nutritional_information' => $recipe->nutritional_information,
       ]
   ], 201);
}


    public function getAllRecipes(Request $request)
    {
        try {


            // $apiKey = $request->header('X-API-Key');
            // $expectedApiKey = env('API_KEY'); // Fetch from environment

            // if ($apiKey !== $expectedApiKey) {
            //     return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
            // }

            // Fetch all recipes with relevant relationships
            $recipes = Recipe::with(['user', 'votes']) // Load user and votes relationships
                ->withCount('votes') // Count the number of votes for each recipe
                ->get()
                ->map(function ($recipe) {

                        // Get all interactions for a recipe
                        $views = Comment::where('recipe_id', $recipe->id)->where('interaction_type', 'view')->get();
                        $ratings = Comment::where('recipe_id', $recipe->id)->where('interaction_type', 'rate')->get();
                        $comments = Comment::where('recipe_id', $recipe->id)->where('interaction_type', 'comment')->get();
                    return [
                        'recipe_id'=>$recipe->id,
                        'title' => $recipe->title,
                        'description' => $recipe->description,
                        'ingredients' => $recipe->ingredients,
                        'instructions' => $recipe->instructions,
                        'cooking_time' => $recipe->cooking_time,
                        'serving_number'=>$recipe->servings,
                        'difficulty_level'=>$recipe->difficulty_level,
                        'chef' => [  // Renamed 'user' to 'chef'
                            'id' => $recipe->user->id,
                            'name' => $recipe->user->name,
                            'profile_picture' => $recipe->user->profile_picture,
                        ],

                        // 'views' => $recipe->views->count(),
                        'ratings' => $ratings,
                        'comments' => $recipe->comments->comment,
                        'rating_count'=>$recipe->ratings->count(),
                        'views_count' => $recipe->views->count(),
                        'comments_count' => $recipe->comments->count(), // Count of comments for the recipe
                        'total_votes' => $recipe->total_votes, // Count of votes for the recipe
                    ];
                });


            return response()->json([
                'response' => "000",
                'response_description'=>"recipes fetched successfully",
                'recipes' => $recipes],
                 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => "999",
                'response_description' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function approve($id)
    {
        $recipe = Recipe::findOrFail($id);

        if ($recipe->status == 'draft') {
            $recipe->status = 'approved';  // Change the status to 'approved'
            $recipe->save();
            return redirect()->back()->with('success', 'Recipe approved successfully.');
        }

        return redirect()->back()->with('error', 'Recipe cannot be approved.');
    }

    // Toggle Status (from approved to revoked or vice versa)
    public function toggleStatus(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        // Toggle between 'approved' and 'revoked'
        if ($recipe->status == 'approved') {
            $recipe->status = 'revoked';  // Set to 'revoked'
        } elseif ($recipe->status == 'revoked') {
            $recipe->status = 'approved';  // Set to 'approved'
        }

        $recipe->save();

        return redirect()->back()->with('success', 'Recipe status updated successfully.');
    }

    public function getRecipeById($id, Request $request)
    {
            $apiKey = $request->header('X-API-Key');
            $expectedApiKey = env('API_KEY'); // Fetch from environment

            if ($apiKey !== $expectedApiKey) {
                return response()->json(['response_description' => 'Unauthorized access. Invalid API Key.'], 401);
            }

        try {
            $recipe = Recipe::findOrFail($id);
            return response()->json([
                'response' => "000",
                'status' => 'success',
                'data' => $recipe
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'response' => "999",
                'status' => 'error',
                'response_description' => 'Recipe not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'response' => "500",
                'status' => 'error',
                'response_description' => 'An error occurred while fetching the recipe.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addRecipe(Request $request)
    {
        // Check for the unique header
        $apiKey = $request->input('api_key'); // Use input() to get data from the body
            $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

            // Check if the provided API key matches the expected API key
            if ($apiKey !== $expectedApiKey) {
                return response()->json([
                    'response' => "401",
                    'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
            }
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'topic_id' => 'required', // Ensures topic_id exists in the topics table
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => "999",
                'response_description' => $validator->errors(),
            ], 422);
        }

        // Create the recipe
        $recipe = Recipe::create($request->all());
        if($recipe){

        // Return the response
        return response()->json([
            'response' => "000",
            'status' => 'success',
            'data' => $recipe,
            'message' => 'Recipe created successfully.'
        ], 201);
        }
            else {
                return response()->json([
                    'response' => "500",
                    'response_description' => 'An error occurred .pleeease try again'], 401);
            }

    }


    public function submitComment(Request $request)
    {
        $apiKey = $request->input('api_key'); // Get the API key from the request
        $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

        // Check if the provided API key matches the expected API key
        if ($apiKey !== $expectedApiKey) {
            return response()->json([
                'response' => "401",
                'response_description' => 'Unauthorized access. Invalid API Key.'
            ], 401);
        }


        // Validate the incoming request data
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'recipe_id' => 'required|exists:recipes,id',
            'comment'   => 'required|string|max:1000' // Adjust the max length as necessary
        ]);

        // Create and save a new comment
        $comment = new Comment;
        $comment->user_id = $validated['user_id'];
        $comment->recipe_id = $validated['recipe_id'];
        $comment->comment = $validated['comment'];
        $comment->save();

        // Return a success response
        return response()->json([
            'response' => "000",
            'response_description' => 'Comment submitted successfully.',
            'comment' => $comment
        ], 201);
    }


    public function submitRating(Request $request)
{
    $apiKey = $request->input('api_key'); // Use input() to get data from the body
    $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

    // Check if the provided API key matches the expected API key
    if ($apiKey !== $expectedApiKey) {
        return response()->json([
            'response' => "401",
            'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
    }

    // Validate the incoming request
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'recipe_id' => 'required|exists:recipes,id',
        'rating' => 'required|integer|min:1|max:5' // Assume rating is between 1 and 5
    ]);

    // Check if the user has already rated this recipe
    $existingRating = Rating::where('user_id', $validated['user_id'])
                        ->where('recipe_id', $validated['recipe_id'])
                        ->first();

    if ($existingRating) {
        // Update the existing rating
        $existingRating->rating = $validated['rating'];
        $existingRating->save();

        return response()->json([
            'response' => "001",
            'response_description' => 'Rating updated successfully.',
            'rating' => $existingRating
        ], 200);
    } else {
        // Create a new rating
        $rating = new Rating;
        $rating->user_id = $validated['user_id'];
        $rating->recipe_id = $validated['recipe_id'];
        $rating->rating = $validated['rating'];
        $rating->save();

        return response()->json([
            'response' => "000",
            'response_description' => 'Rating submitted successfully.',
            'rating' => $rating
        ], 201);
    }
}


public function recipeView(Request $request)
{

    $apiKey = $request->input('api_key'); // Use input() to get data from the body
    $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

    // Check if the provided API key matches the expected API key
    if ($apiKey !== $expectedApiKey) {
        return response()->json([
            'response' => "401",
            'response_description' => 'Unauthorized access. Invalid API Key.'
        ], 401);
    }

    // Validate the incoming request
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'recipe_id' => 'required|exists:recipes,id',
    ]);

    // Check if the user has already viewed this recipe
    $existingView =View::where('user_id', $validated['user_id'])
                    ->where('recipe_id', $validated['recipe_id'])
                    ->first();

    if ($existingView) {
        // If the view already exists, return a message indicating it's already viewed
        return response()->json([
            'response' => "001",
            'response_description' => 'View already recorded.',
            'view' => $existingView
        ], 200);
    } else {
        // Create a new view record
        $view = new View;
        $view->user_id = $validated['user_id'];
        $view->recipe_id = $validated['recipe_id'];
        $view->save();

        return response()->json([
            'response' => "000",
            'response_description' => 'View recorded successfully.',
            'view' => $view
        ], 201);
    }
}

    private function incrementRecipeViews($recipe_id)
{
    // Increment the view count for the recipe by 1
    $recipe = Comment::findOrFail($recipe_id);
    $recipe->increment('views'); // Increments the view count by 1
}


}
