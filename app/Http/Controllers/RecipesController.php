<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

// Topic
use App\Models\Topic;
//comment
use App\Models\Comment;
//user
use App\Models\User;

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
          $apiKey = $request->header('X-API-Key');
          $expectedApiKey = 'ABDI'; // Replace with your actual unique key

          if ($apiKey !== $expectedApiKey) {
              return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
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
        'user_id' => 'required|integer',
        'image' => 'nullable|string', // Image validation
        'tags' => 'nullable|string', // Tags should be a string
        'difficulty_level' => 'required|string|in:easy,medium,hard', // Difficulty level validation
        'nutritional_information' => 'nullable|string', // Optional nutritional info
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        // Return a custom error response
        return response()->json([
            'message' => 'Please fill all the required fields.',
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
        'user_id' => $request->user_id, // Assumes user is authenticated
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
       'message' => 'Recipe submitted successfully!',
       'recipe' => [
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

            $apiKey = $request->header('X-API-Key');
            $expectedApiKey = 'ABDI'; // Replace with your actual unique key

            if ($apiKey !== $expectedApiKey) {
                return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
            }

            // Fetch all recipes with relevant relationships
            $recipes = Recipe::with(['user', 'votes']) // Load user and votes relationships
                ->withCount('votes') // Count the number of votes for each recipe
                ->get()
                ->map(function ($recipe) {
                    $recipeEngagement = $recipe->comments;
                    return [
                        'id' => $recipe->id,
                        'title' => $recipe->title,
                        'description' => $recipe->description,
                        'ingredients' => $recipe->ingredients,
                        'instructions' => $recipe->instructions,
                        'cooking_time' => $recipe->cooking_time,
                        'chef' => [  // Renamed 'user' to 'chef'
                            'id' => $recipe->user->id,
                            'name' => $recipe->user->name,
                            'profile_picture' => $recipe->user->profile_picture,
                        ],
                        'comments' => $recipeEngagement['comments'], // Renamed 'comments' to 'comment,
                        // 'rating' => $recipeEngagement['rating'],
                        // 'commenters'=>User::find($recipeEngagement[0]->user_id)->value('name'),
                        'comments_count' => $recipe->comments->count(), // Count of comments for the recipe
                        'total_votes' => $recipe->votes_count, // Count of votes for the recipe
                    ];
                });

            return response()->json(['recipes' => $recipes], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
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
        // Check for the unique header
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = 'ABDI'; // Replace with your actual unique key

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        try {
            $recipe = Recipe::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $recipe
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the recipe.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addRecipe(Request $request)
    {
        // Check for the unique header
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = 'ABDI'; // Replace with your actual unique key

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access. Invalid API Key.'], 401);
        }


        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'topic_id' => 'required', // Ensures topic_id exists in the topics table
        ]);

        // Create the recipe
        $recipe = Recipe::create($request->all());

        // Return the response
        return response()->json([
            'status' => 'success',
            'data' => $recipe,
            'message' => 'Recipe created successfully.'
        ], 201);
    }

    public function submitComment(Request $request)
{
    // Validate the request
    $validator = Validator::make($request->all(), [
        'recipe_id' => 'required|exists:recipes,id',
        'comment' => 'required|string',
        'rating' => 'nullable|integer|between:1,5', // Rating must be between 1 and 5
    ]);

    // Check for validation errors
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Create the comment
    $comment = Comment::create([
        'recipe_id' => $request->recipe_id,
        'user_id' => $request->user_id, // Assuming user is authenticated
        'comment' => $request->comment,
        'rating' => $request->rating,
    ]);

    return response()->json([
        'message' => 'Comment submitted successfully',
        'comment' => $comment,
    ], 201);
}


}
