<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;


class TopicsController extends Controller
{

    public function index()
    {
        // Get topics with their recipes and paginate
        $topics = Topic::with('recipes')->paginate(10);

        // Count topics by status
        $statusCounts = Topic::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Optionally, convert to an array if needed
        $statusCounts = $statusCounts->toArray();

        // Debug the status counts
        // dd($statusCounts);

        return view('Topic.index', compact('topics', 'statusCounts'));
    }

    public function addTopic(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|string|max:255|unique:topics,name',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'sometimes|string|in:open,closed',
            ]);

            // Set the default status to 'open' if not provided
            $request->merge(['status' => $request->input('status', 'open')]);

            // Create a new topic using the validated data
            $topic = Topic::create($request->all());

            // Return a success response
            return redirect()->route('topics.index')->with('success', 'Topic created successfully.');
        } catch (\Exception $e) {
            // Return an error response if there's an exception
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function create()
    {
        // Return a view with the single topic (or JSON)
        return view('Topic.create');
    }

    public function getAllTopics(Request $request)
    {
        try {

            $apiKey = $request->header('X-API-Key');
            $expectedApiKey = 'ABDI'; // Replace with your actual unique key

            if ($apiKey !== $expectedApiKey) {
                return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
            }
            // Fetch all topics with their related recipes
            $topics = Topic::with('recipes')->get();

            // Prepare the response data
            $responseData = $topics->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'name' => $topic->name,
                    'description' => $topic->description,
                    'start_date' => $topic->start_date,
                    'end_date' => $topic->end_date,
                    'status' => $topic->status,
                    'recipe_count' => $topic->recipeCount(),
                    'latest_recipe' => $topic->recipeSummary(), // Include the latest recipe summary if needed
                    'recipes' => $topic->recipes, // Include related recipes
                ];
            });

            return response()->json(['topics' => $responseData], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
