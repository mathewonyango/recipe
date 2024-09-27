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


class UsersController extends Controller
{


    public function register(Request $request)
    {
        // Check API Key from environment variable
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = env('API_KEY'); // Fetch from environment

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'push_notification' => 'required|in:allow,deny',
            'password' => 'required|string|min:6', // Minimum length can be adjusted
            'notification_preferences' => 'nullable|array', // Expecting an array for notification preferences
        ]);

        // If validation fails, return error messages
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the new user
        try {
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'username' => $request->username,
                'push_notification' => $request->push_notification,
                'password' => Hash::make($request->password), // Hash the password
                'notification_preferences' => $request->notification_preferences ? json_encode($request->notification_preferences) : null, // Encode if present
                'role' => 'user', // Default role for general users
            ]);

            return response()->json(['message' => 'Registration successful!', 'user' => $user], 201);

        } catch (\Exception $e) {
            // Return a detailed error message in case of failure
            return response()->json([
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function registerChef(Request $request)
    {
        // Check for API key
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = env('API_KEY'); // Fetch from environment

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
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
                'payment_status' => 'nullable|string', // Optional
                'social_media_links' => 'nullable|array', // Optional (array of links)
                'social_media_links.*' => 'nullable|url', // Ensure each link is a valid URL

            ]);

            // Return validation errors if any
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
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
            // Save the new chef to the database
            $chef->save();

            $chefProfile = User::with('recipes', 'votes', 'events')->find($chef->id);


            // Return the registered data as a response
            return response()->json([
                'status' => 'success',
                'message' => 'Chef registered successfully!',
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
                    'events' => $chefProfile->events,
                    'recipe_count' => $chefProfile->recipes()->count(),
                    'total_votes' => $chefProfile->votes()->count(),
                    'payment_status' => $chef->payment_status,
                    'social_media_links' => json_decode($chef->social_media_links, true), // Return as array
                    'role' => $chef->role,
                    ]
            ], 201);
        } catch (\Illuminate\Database\QueryException $ex) {
            // Catch database-related errors (e.g., duplicate entry, foreign key constraint failures)
            return response()->json([
                'status' => 'error',
                'message' => 'Database error',
                'details' => $ex->getMessage(),
            ], 500);
        } catch (\Exception $ex) {
            // Catch any general errors
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'details' => $ex->getMessage(),
            ], 500);
        }
    }



    public function loginChef(Request $request)
    {
        // Check API Key
        $apiKey = $request->header('X-API-Key');
            $expectedApiKey = env('API_KEY'); // Fetch from environment

            if ($apiKey !== $expectedApiKey) {
                return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
            }

        // Validate the request data
        $request->validate([
            'username_or_email' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // Check if the provided username or email exists in the database
            $user = User::where('username', $request->username_or_email)
                ->orWhere('email', $request->username_or_email)
                ->first();

            // If user not found or password does not match
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials.'], 401);
            }

            // Fetch the chef's profile data with related data
            $chefProfile = User::with('recipes', 'votes', 'events')->find($user->id);

            if (!$chefProfile) {
                return response()->json(['message' => 'Chef profile not found.'], 404);
            }

            // Prepare the response data
            $responsePayload = [
                'message' => 'Login successful!',
                'user' => [
                    'id' => $chefProfile->id,
                    'name' => $chefProfile->name,
                    'bio' => $chefProfile->bio,
                    'payment_status' => $chefProfile->payment_status,
                    'social_media_links' => $chefProfile->social_media_links,
                    'role' => $chefProfile->role,
                    'profile_picture' => $chefProfile->profile_picture,
                    'recipes' => $chefProfile->recipes,
                    'votes' => $chefProfile->votes,
                    'events' => $chefProfile->events,
                    'recipe_count' => $chefProfile->recipes()->count(),
                    'total_votes' => $chefProfile->votes()->count(),
                ],
            ];

            return response()->json($responsePayload, 200);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }




    public function loginUser(Request $request)
    {
        // Check API Key
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = env('API_KEY'); // Fetch from environment

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'username_or_email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed.', 'errors' => $validator->errors()], 400);
        }

        try {
            // Check if the provided username or email exists in the database
            $user = User::where('username', $request->username_or_email)
                ->orWhere('email', $request->username_or_email)
                ->first();

            // If user not found or password does not match
            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials.'], 401);
            }

            // Load additional relationships
            $user->load('votes.recipe', 'events');

            // Prepare the response data based on the user's role
            $responsePayload = [
                'message' => 'Login successful!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'status' => $user->status, // Assuming there's a status column
                    'bio' => $user->bio, // Assuming there's a bio column
                    'role' => $user->role, // Role (e.g., voter, chef)
                    'profile_picture' => $user->profile_picture,
                    'social_media_links' => json_decode($user->social_media_links), // Decode JSON for better readability
                ],
            ];

            // Additional data for chefs
            if ($user->role === 'chef') {
                $chefProfile = User::with('recipes', 'votes', 'events')->find($user->id);

                if ($chefProfile) {
                    $responsePayload['user']['recipes'] = $chefProfile->recipes;
                    $responsePayload['user']['recipe_voted_for'] = $chefProfile->votes;
                    $responsePayload['user']['events'] = $chefProfile->events;
                    $responsePayload['user']['recipe_count'] = $chefProfile->recipes()->count();
                    $responsePayload['user']['total_votes'] = $chefProfile->votes()->count();
                }
            } else {
                // Additional data for normal users
                $responsePayload['user']['recipes_voted_for'] = $user->votes->map(function ($vote) {
                    return [
                        'recipe_id' => $vote->recipe_id,
                        'recipe_title' => $vote->recipe->title, // Adjust according to your Recipe model
                        // Add other recipe details as necessary
                    ];
                });

                // Optionally include events for normal users as well
                $responsePayload['user']['events_participated'] = $user->events->map(function ($event) {
                    return [
                        'event_id' => $event->id,
                        'event_name' => $event->name, // Adjust according to your Event model
                        // Add other event details as necessary
                    ];
                });
            }

            return response()->json($responsePayload, 200);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


   // Forgot password method to generate token
   public function forgotPassword(Request $request)
   {
       // Validate the email
       $validator = Validator::make($request->all(), [
           'email' => 'required|email|exists:users,email'
       ]);

       if ($validator->fails()) {
           return response()->json([
               'message' => 'Validation failed',
               'errors' => $validator->errors(),
           ], 422);
       }

       // Generate reset token
       $token = Str::random(60);

       // Store token in password_resets table
       DB::table('password_resets')->updateOrInsert(
           ['email' => $request->email],
           ['token' => $token, 'created_at' => Carbon::now()]
       );

       // Send token back to the app (this can also be emailed)
       $user=User::where('email', $request->email)->first();
       return response()->json([
           'message' => 'Password reset token generated.',
           'token' => $token,
           'name' => $user->name,
           'email' => $user->email,

       ], 200);
   }

    // Reset password method
    public function resetPassword(Request $request)
    {
        // Validate the token, email, and new password
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if the token is valid
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return response()->json([
                'message' => 'Invalid token or email.'
            ], 400);
        }

        // Check if the token is expired (valid for 1 hour)
        $tokenExpired = Carbon::parse($reset->created_at)->addHour()->isPast();
        if ($tokenExpired) {
            return response()->json([
                'message' => 'Token has expired.',
            ], 400);
        }

        // Reset the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token after a successful password reset
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password has been reset successfully.',
            'user' => $user,
            'new-password' => $request->password
        ], 200);
    }





    // Fetch Chef Profile Data



    public function getChefs(Request $request)
    {

        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = env('API_KEY'); // Fetch from environment

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        try {
            // Fetch all users with the role of 'chef'
            $chefs = User::where('role', 'chef')
                ->withCount('votes') // Get the total votes for each chef
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
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function Users(Request $request)
    {

        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = env('API_KEY'); // Fetch from environment

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
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
                    'profile_picture' => $user->profile_picture,
                    // 'cuisine_type' => $user->cuisine_type,
                    'location' => $user->location,
                    // 'experience_level' => $user->experience_level,
                    // 'certification' => $user->certification,
                    'bio' => $user->bio,
                    // 'recipe_count' => $user->recipes()->count(),
                    // 'recipe_submitted' => $user->recipes,
                    // 'total_votes' => $user->totalVotes(), // The total votes retrieved by withCount
                ];


            });

            // Sort chefs by total votes in descending order
            // $sortedUsers = $responseData->sortByDesc('total_votes')->values()->all();

            // Assign voting positions based on sorted order

            return response()->json(['users' => $responseData], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    // Update Chef Profile Data
    public function updateProfile($id, Request $request)
    {
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = 'ABDI'; // Replace with your actual unique key

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        // Validate the incoming request data
        $request->validate([
            'bio' => 'string|nullable',
            'recipe_submitted' => 'integer|nullable',
            'payment_status' => 'string|nullable',
            'social_media_links' => 'array|nullable',
            'event_participated' => 'array|nullable',
            'profile_picture' => 'string|nullable',
        ]);

        // Find the chef
        $chef = User::find($id);

        if (!$chef) {
            return response()->json(['message' => 'Chef not found.'], 404);
        }

        // Update the chef's profile data
        $chef->bio = $request->input('bio', $chef->bio);
        $chef->recipe_submitted = $request->input('recipe_submitted', $chef->recipe_submitted);
        $chef->payment_status = $request->input('payment_status', $chef->payment_status);
        $chef->social_media_links = $request->input('social_media_links', $chef->social_media_links);
        $chef->event_participated = $request->input('event_participated', $chef->event_participated);
        $chef->profile_picture = $request->input('profile_picture', $chef->profile_picture);

        $chef->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'chef' => $chef,
        ]);
    }


    public function addRecipe(Request $request)
    {
        // Check for the unique header
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = env('API_KEY'); // Fetch from environment

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
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
