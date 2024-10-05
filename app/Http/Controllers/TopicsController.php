<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



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

    }


    public function create()
    {
        // Return a view with the single topic (or JSON)
        return view('Topic.create');
    }

    public function getAllTopics(Request $request)
    {
        try {
            $apiKey = $request->input('api_key'); // Use input() to get data from the body
            $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

            // Check if the provided API key matches the expected API key
            if ($apiKey !== $expectedApiKey) {
                return response()->json([
                    'response'=> "999",
                    'status' => 'error',
                    'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
            }

            // Initialize the query builder
            $query = Topic::with('recipes');

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Fetch the latest topics if 'latest' is passed
            if ($request->has('latest')) {
                // You can define what "latest" means, e.g., the last added or modified topics
                $query->orderBy('created_at', 'desc')->take(2); // Adjust the number as needed
            }

            // Filter by date range if 'start_date' and 'end_date' are provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('start_date', [$request->input('start_date'), $request->input('end_date')]);
            }

            // Fetch the filtered topics
            $topics = $query->get();


            // Prepare the response data
            $responseData = $topics->map(function ($topic) {

                $currentDate = Carbon::now();
                $status = $topic->end_date > $currentDate ? 'open' : 'closed'; //
                return [

                        'topic_id'=>$topic->id,
                        'topic_title'=>$topic->name,
                        'Topic_status'=>$status,
                        'description'=>$topic->description,
                        'start_date'=>$topic->start_date,
                        'end_date'=>$topic->end_date,
                        'total_votes' => $topic->totalVotes(),
                        'total_chefs' => $topic->totalChefs(),
                        'total_comments' => $topic->totalComments(),
                        'comments' => $topic->comments,
                        'average_rating' => $topic->averageRatings(),
                        'winner' => $topic->winner(),
                        'chef_rankings' => $topic->chefRankings(),
                ];
            });

            return response()->json([
                'response'=>"000",
                'response_description' => 'Topics fetched successfully!',
                'topics' => $responseData],

            200);
        } catch (\Exception $e) {
            return response()->json([
                'response'=>"999",
                'response_description' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
