<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password; // Include this at the top

use Illuminate\Support\Facades\DB; // For database operations
use Illuminate\Support\Str; // For generating the random token
use Illuminate\Support\Carbon; // For handling time and token expiration
use App\Models\User; // For the User model
//Comment
use App\models\Comment;
use App\Models\Vote;
use App\Models\Event;



class UsersController extends Controller
{


    public function register(Request $request)
    {
        $apiKey = $request->input('api_key'); // Use input() to get data from the body
        $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

        // Check if the provided API key matches the expected API key
        if ($apiKey !== $expectedApiKey) {
            return response()->json([
                'response' => "401",
                'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6', // Minimum length can be adjusted
            'notification_preferences' => 'nullable|array', // Expecting an array for notification preferences
        ]);

        // If validation fails, return error messages
        if ($validator->fails()) {
            return response()->json([
                'response' => "999",
                // 'response_description' => 'All fields are required.',
                'response_description' => $validator->errors(),
            ], 422);
        }

        // Create the new user
        try {
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'username' => $request->username,
                'push_notification' => 'allow',
                'password' => Hash::make($request->password), // Hash the password
                'notification_preferences' => json_encode($request->notification_preferences), // Optional, store as JSON
                'role' => 'user', // Default role for general users
            ]);
            return response()->json([
                'response' => "000",
                'response_description' => 'Registration successful!', 'user' => $user], 201);

        } catch (\Exception $e) {
            // Return a detailed error message in case of failure
            return response()->json([
                'response'=>"500",
                'response_description' => 'Registration failed. Please try again.',
                // 'response_description' => $e->getMessage(),
            ], 500);
        }
    }


    public function registerChef(Request $request)
    {
        $apiKey = $request->input('api_key'); // Use input() to get data from the body
            $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

            // Check if the provided API key matches the expected API key
            if ($apiKey !== $expectedApiKey) {
                return response()->json([
                    'response' => "401",
                    'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
            }

        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:6',
                'experience_level' => 'required|in:Beginner,Intermediate,Professional',
                'location' => 'required|string',
                'profile_picture' => 'nullable|string', // Optional
                'cuisine_type' => 'nullable|string', // Optional
                'certification' => 'nullable|string', // Optional
                'bio' => 'nullable|string', // Optional
                'push_notification'=> 'nullable|in:allow,deny',
                'notification_preferences' => 'nullable|array', // Optional (array of preferences)
                'payment_status' => 'nullable|string', // Optional
                'social_media_links' => 'nullable|array', // Optional (array of links)
                'social_media_links.*' => 'nullable|url', // Ensure each link is a valid URL

            ]);

            // Return validation errors if any
            if ($validator->fails()) {
                return response()->json([
                    'response'=>"999",
                    'status' => 'error',
                    'response_description' =>$validator->errors(),
                ], 422);
            }

            // Create new Chef
            $chef = new User();
            $chef->name = $request->full_name;
            $chef->email = $request->email;
            $chef->username = $request->username;
            $chef->password = Hash::make($request->password);
            $chef->experience_level = $request->experience_level;
            $chef->location = $request->location;
            $chef->profile_picture = $request->profile_picture; // Optional
            $chef->cuisine_type = $request->cuisine_type; // Optional
            $chef->certification = $request->certification; // Optional
            $chef->bio = $request->bio; // Optional
            $chef->payment_status = $request->payment_status; // Optional
            $chef->social_media_links = json_encode($request->social_media_links); // Optional, store as JSON
            $chef->role = 'chef'; // default role
            $chef->status = 'active'; // default status
            $chef->approval_status = 'pending'; // default approval status
            $chef->recipes_count = 0; // default value for recipes count
            $chef->payment_status='unpaid';
            $chef->push_notification=$request->push_notification;
            $chef->notification_preferences = json_encode($request->notification_preferences); // Optional, store as JSON
            // Save the new chef to the database
            $chef->save();

            $chefProfile = User::with('recipes', 'votes', 'events')->find($chef->id);


            // Return the registered data as a response
            return response()->json([
                'response'=>"000",
                'status' => 'success',
                'response_description' => 'Chef registered successfully!',
                'chef' => [
                    'name' => $chef->name,
                    'email' => $chef->email,
                    'username' => $chef->username,
                    'experience_level' => $chef->experience_level,
                    'location' => $chef->location,
                    'profile_picture' => $chef->profile_picture,
                    'cuisine_type' => $chef->cuisine_type,
                    'certification' => $chef->certification,
                    'bio' => $chef->bio,
                    'recipes' => $chefProfile->recipes,
                    'votes' => $chefProfile->votes,

                    // 'events' => $chefProfile->events,
                    'recipe_count' => $chefProfile->recipes()->count(),
                    'total_votes' => $chefProfile->votes()->count(),
                    'payment_status' => $chef->payment_status,
                    'social_media_links' => json_decode($chef->social_media_links, true), // Return as array
                    'role' => $chef->role,
                    'status' => $chef->status,
                    'push_notification' => $chef->push_notification ?? 'allow',
                    'notification_preferences'=>$chef->notification_preferences ?? ['email'],
                    ]
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            // Catch database-related errors (e.g., duplicate entry, foreign key constraint failures)
            return response()->json([
                'response'=>"500",
                'status' => 'error',
                'response_description' => 'Network connection error',
                'details' => $ex->getMessage(),
            ], 500);
        } catch (\Exception $ex) {
            // Catch any general errors
            return response()->json([
                'response'=>"501",
                'response_description' => 'server internal error',
                'status' => 'error',
                'message' => 'Something went wrong',
                'details' => $ex->getMessage(),
            ], 500);
        }
    }
    public function updateChef(Request $request)
    {
        $apiKey = $request->input('api_key');
        $expectedApiKey = env('API_KEY');

        if ($apiKey !== $expectedApiKey) {
            return response()->json([
                'response' => "401",
                'status' => 'error',
                'response_description' => 'Unauthorized access. Invalid API Key.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $request->user_id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $request->user_id,
            'password' => 'nullable|string|min:6',
            'experience_level' => 'nullable|in:Beginner,Intermediate,Professional',
            'location' => 'nullable|string',
            'profile_picture' => 'nullable|string',
            'cuisine_type' => 'nullable|string',
            'certification' => 'nullable|string',
            'bio' => 'nullable|string',
            'push_notification' => 'nullable|in:allow,deny',
            'notification_preferences' => 'nullable|array',
            'payment_status' => 'nullable|string',
            'social_media_links' => 'nullable|array',
            'social_media_links.*' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => "999",
                'status' => 'error',
                'response_description' => 'Validation failed',
                'response_description' => $validator->errors()
            ], 400);
        }

        try {
            $chef = User::findOrFail($request->user_id);
            $changes = [];

            if ($request->filled('full_name') && $request->full_name !== $chef->name) {
                $chef->name = $request->full_name;
                $changes['full_name'] = $request->full_name;
            }
            if ($request->filled('email') && $request->email !== $chef->email) {
                $chef->email = $request->email;
                $changes['email'] = $request->email;
            }
            if ($request->filled('username') && $request->username !== $chef->username) {
                $chef->username = $request->username;
                $changes['username'] = $request->username;
            }
            if ($request->filled('password')) {
                $chef->password = Hash::make($request->password);
                $changes['password'] = 'Updated';
            }
            if ($request->filled('experience_level') && $request->experience_level !== $chef->experience_level) {
                $chef->experience_level = $request->experience_level;
                $changes['experience_level'] = $request->experience_level;
            }
            if ($request->filled('location') && $request->location !== $chef->location) {
                $chef->location = $request->location;
                $changes['location'] = $request->location;
            }
            if ($request->filled('profile_picture') && $request->profile_picture !== $chef->profile_picture) {
                $chef->profile_picture = $request->profile_picture;
                $changes['profile_picture'] = $request->profile_picture;
            }
            if ($request->filled('cuisine_type') && $request->cuisine_type !== $chef->cuisine_type) {
                $chef->cuisine_type = $request->cuisine_type;
                $changes['cuisine_type'] = $request->cuisine_type;
            }
            if ($request->filled('certification') && $request->certification !== $chef->certification) {
                $chef->certification = $request->certification;
                $changes['certification'] = $request->certification;
            }
            if ($request->filled('bio') && $request->bio !== $chef->bio) {
                $chef->bio = $request->bio;
                $changes['bio'] = $request->bio;
            }
            if ($request->filled('push_notification') && $request->push_notification !== $chef->push_notification) {
                $chef->push_notification = $request->push_notification;
                $changes['push_notification'] = $request->push_notification;
            }
            if ($request->filled('notification_preferences') && $request->notification_preferences !== $chef->notification_preferences) {
                $chef->notification_preferences = $request->notification_preferences;
                $changes['notification_preferences'] = $request->notification_preferences;
            }
            if ($request->filled('payment_status') && $request->payment_status !== $chef->payment_status) {
                $chef->payment_status = $request->payment_status;
                $changes['payment_status'] = $request->payment_status;
            }
            if ($request->filled('social_media_links') && $request->social_media_links !== $chef->social_media_links) {
                $chef->social_media_links = $request->social_media_links;
                $changes['social_media_links'] = $request->social_media_links;
            }

            if (!empty($changes)) {
                $chef->save();
            }

            return response()->json([
                'response' => "000",
                'response_description' => 'Chef updated successfully!',
                'chef_data' => [

                    'full_name' => $chef->name ?? '',
                    'email' => $chef->email ?? '',
                    'username' => $chef->username ?? '',
                    'role'=>$chef->role,
                    'approval_status'=>$chef->approval_status,
                    'recipe_count'=>$chef->recipes->count(),
                    'experience_level'=>$chef->experience_level ?? '',
                    'cuisine_type' => $chef->cuisine_type ?? '',
                    'certification' => $chef->certification ?? '',
                    'location' => $chef->location ?? '',
                    'profile_picture' => $chef->profile_picture ?? '',
                    'cuisine_type' => $chef->cuisine_type ?? '',
                    'certification' => $chef->certification ?? '',
                    'bio' => $chef->bio ?? '',
                    'push_notification' => $chef->push_notification ?? '',
                    'notification_preferences' => json_decode($chef->notification_preferences) ?? '',
                    'social_media_links' =>json_decode($chef->social_media_links) ?? ' ',
                    'event_participated' => $chef->event_participated ?? 0, // Set to 0 if null
                    'payment'=>$chef->payment,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'response' => "500",
                'response_description' => 'Update failed. Please try again.',
            ], 500);
        }
    }













    // Fetch Chef Profile Data



    public function getChefs(Request $request)
    {
        $apiKey = $request->input('api_key'); // Use input() to get data from the body
        $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

        // Check if the provided API key matches the expected API key
        if ($apiKey !== $expectedApiKey) {
            return response()->json(['response_description' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        try {
            // Fetch all users with the role of 'chef'
            $chefs = User::where('role', 'chef')
                ->withCount('votes') // Get the total votes for each chef
                ->with('comments') // Get the total comments for each chef
                ->get();

            // Prepare the response data
            $responseData = $chefs->map(function ($chef) {
                return [
                    'id' => $chef->id,
                    'name' => $chef->name,
                    'email' => $chef->email,
                    'profile_picture' => $chef->profile_picture,
                    'cuisine_type' => $chef->cuisine_type,
                    'location' => $chef->location,
                    'experience_level' => $chef->experience_level,
                    'certification' => $chef->certification,
                    'bio' => $chef->bio,
                    'comments_and_rating'=>$chef->comments,
                    'recipe_count' => $chef->recipes()->count(),
                    'recipe_submitted' => $chef->recipes,
                    'total_votes' => $chef->totalVotes(), // The total votes retrieved by withCount
                ];


            });

            // Sort chefs by total votes in descending order
            $sortedChefs = $responseData->sortByDesc('total_votes')->values()->all();

            // Assign voting positions based on sorted order
            foreach ($sortedChefs as $index => $chef) {
                $chef['voting_position'] = $index + 1; // Position starts from 1
            }

            return response()->json(['chefs' => $sortedChefs], 200);
        } catch (\Exception $e) {
            return response()->json(['response_description' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function fetchUsers(Request $request)
    {

        $apiKey = $request->input('api_key'); // Use input() to get data from the body
        $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

        // Check if the provided API key matches the expected API key
        if ($apiKey !== $expectedApiKey) {
            return response()->json([
                'response'=>"401",
                'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        try {
            // Fetch all users with the role of 'chef'
            $user = User::where('role', 'user')
                ->withCount('votes') // Get the total votes for each chef
                ->get();


            // Prepare the response data
            $responseData = $user->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role'=> $user->role,
                    'profile_picture' => $user->profile_picture,
                    'notification_preferences' => $user->notification_preferences ?? ['email'],
                    // 'cuisine_type' => $user->cuisine_type,
                    'push_notification'=>$user->push_notification,
                    'location' => $user->location,
                    'bio' => $user->bio,

                ];


            });

            // Sort chefs by total votes in descending order
            // $sortedUsers = $responseData->sortByDesc('total_votes')->values()->all();

            // Assign voting positions based on sorted order

            return response()->json([
                'response'=>"000",
                'response_description'=>"users fetched succesfully",
                'users' => $responseData],
                200);
        } catch (\Exception $e) {
            return response()->json(['response_description' => 'An error occurred: ' . $e->getMessage()],
             500);
        }
    }
    public function fetchChefs(Request $request)
    {

        $apiKey = $request->input('api_key'); // Use input() to get data from the body
        $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

        // Check if the provided API key matches the expected API key
        if ($apiKey !== $expectedApiKey) {
            return response()->json([
                'response'=>"401",
                'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        try {
            // Fetch all users with the role of 'chef'
            $user = User::where('role', 'chef')
                ->withCount('votes') // Get the total votes for each chef
                ->get();


            // Prepare the response data
            $responseData = $user->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role'=> $user->role,
                    'profile_picture' => $user->profile_picture,
                    'notification_preferences' => $user->notification_preferences ?? ['email'],
                    // 'cuisine_type' => $user->cuisine_type,
                    'push_notification'=>$user->push_notification,
                    'location' => $user->location,
                    'bio' => $user->bio,

                ];


            });

            // Sort chefs by total votes in descending order
            // $sortedUsers = $responseData->sortByDesc('total_votes')->values()->all();

            // Assign voting positions based on sorted order

            return response()->json([
                'response'=>"000",
                'response_description'=>"users fetched succesfully",
                'users' => $responseData],
                200);
        } catch (\Exception $e) {
            return response()->json(['response_description' => 'An error occurred: ' . $e->getMessage()],
             500);
        }
    }


    // Update Chef Profile Data
    public function updateUser(Request $request)
    {
        $apiKey = $request->input('api_key');
        $expectedApiKey = env('API_KEY');

        if ($apiKey !== $expectedApiKey) {
            return response()->json([
                'response' => "401",
                'response_description' => 'Unauthorized access. Invalid API Key.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $request->user_id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $request->user_id,
            'password' => 'nullable|string|min:6',
            'location' => 'nullable|string',
            'profile_picture' => 'nullable|string',
            'bio' => 'nullable|string',
            'push_notification' => 'nullable|in:allow,deny',
            'notification_preferences' => 'nullable|array',
            'social_media_links' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => "999",
                'response_description' => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);

            // Update user fields only if provided in the request
            if ($request->filled('full_name')) {
                $user->name = $request->full_name;
            }
            if ($request->filled('email')) {
                $user->email = $request->email;
            }
            if ($request->filled('username')) {
                $user->username = $request->username;
            }
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            if ($request->filled('location')) {
                $user->location = $request->location;
            }
            if ($request->filled('profile_picture')) {
                $user->profile_picture = $request->profile_picture;
            }
            if ($request->filled('bio')) {
                $user->bio = $request->bio;
            }
            if ($request->filled('push_notification')) {
                $user->push_notification = $request->push_notification;
            }
            if ($request->filled('notification_preferences')) {
                $user->notification_preferences = $request->notification_preferences;
            }
            if ($request->filled('social_media_links')) {
                $user->social_media_links = $request->social_media_links;
            }

            // Save the updated user
            $user->save();

            // Prepare the user response data
            return response()->json([
                'response' => "000",
                'response_description' => 'User updated successfully!',
                'user' => [
                    'user_id' => $user->id,
                    'full_name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                    'role'=>$user->role,
                    'username' => $user->username ?? '',
                    'location' => $user->location ?? '',
                    'profile_picture' => $user->profile_picture ?? '',
                    'bio' => $user->bio ?? '',
                    'push_notification' => $user->push_notification ?? '',
                    'notification_preferences' => json_decode($user->notification_preferences) ?? '',
                    'social_media_links' => $user->social_media_links ?? '',
                    'event_participated' => $user->event_participated ?? 0, // Set to 0 if null
                    'payment'=>$user->payment,

                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'response' => "500",
                'response_description' => 'Update failed. Please try again.',
            ], 500);
        }
    }





    public function addRecipe(Request $request)
    {
        // Check for the unique header
        // Fetch the API key from the request body
            $apiKey = $request->input('api_key'); // Use input() to get data from the body
            $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

            // Check if the provided API key matches the expected API key
            if ($apiKey !== $expectedApiKey) {
                return response()->json([
                    'response'=>"401",
                    'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
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
            'response'=>"000",
            'status' => 'success',
            'data' => $recipe,
            'response_description' => 'Recipe created successfully.'
        ], 201);
    }
}
