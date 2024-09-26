<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;


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


    public function getAllRecipes(Request $request)
    {
        try {
            // Fetch all recipes with relevant relationships
            $recipes = Recipe::with(['user', 'votes']) // Load user and votes relationships
                ->get()
                ->map(function ($recipe) {
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
                        'total_votes' => $recipe->votes->count(), // Count of votes for the recipe
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

}
