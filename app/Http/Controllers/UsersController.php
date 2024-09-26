<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password; // Include this at the top



class UsersController extends Controller
{

    public function register(Request $request)
    {
        // Check API Key
        $apiKey = $request->header('X-API-Key');
        $expectedApiKey = 'ABDI'; // Replace with your actual unique key

        if ($apiKey !== $expectedApiKey) {
            return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
        }

        // Validate the request data
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8', // Minimum length can be adjusted
        ]);


        // Create the new user
        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password), // Hashing the password
            'notification_preferences' => json_encode($request->notification_preferences), // Convert array to JSON
            'role' => 'user', // Default role for general users

        ]);

        return response()->json(['message' => 'Registration successful!', 'user' => $user], 201);
    }

public function registerChef(Request $request)
{
    $apiKey = $request->header('X-API-Key');
    $expectedApiKey = 'ABDI'; // Replace with your actual unique key

    if ($apiKey !== $expectedApiKey) {
        return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
    }

    $validator = Validator::make($request->all(), [
        'full_name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'username' => 'required|string|unique:users,username',
        'password' => 'required|string|min:6',
        'experience_level' => 'required|in:Beginner,Intermediate,Professional',
        'location' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }


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
    $chef->role ='chef'; // default

    $chef->save();

    return response()->json(['message' => 'Chef registered successfully!'], 201);
}


public function loginChef(Request $request)
{
    // Check API Key
    $apiKey = $request->header('X-API-Key');
    $expectedApiKey = 'ABDI'; // Replace with your actual unique key

    if ($apiKey !== $expectedApiKey) {
        return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
    }

    // Validate the request data
    $request->validate([
        'username_or_email' => 'required|string',
        'password' => 'required|string',
    ]);

    // Check if the provided username or email exists in the database
    $user = User::where('username', $request->username_or_email)
                 ->orWhere('email', $request->username_or_email)
                 ->first();

    // If user not found or password does not match
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    // Create a response payload (you can modify this to include a token if needed)
    $responsePayload = [
        'message' => 'Login successful!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'profile_picture' => $user->profile_picture,
            // Add more fields if needed
        ],
    ];

    return response()->json($responsePayload, 200);
}


public function loginUser(Request $request)
{
    // Check API Key
    $apiKey = $request->header('X-API-Key');
    $expectedApiKey = 'ABDI'; // Replace with your actual unique key

    if ($apiKey !== $expectedApiKey) {
        return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
    }

    // Validate the request data
    $request->validate([
        'username_or_email' => 'required|string',
        'password' => 'required|string',
    ]);

    // Check if the provided username or email exists in the database
    $user = User::where('username', $request->username_or_email)
                 ->orWhere('email', $request->username_or_email)
                 ->first();

    // If user not found or password does not match
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    // Create a response payload (you can modify this to include a token if needed)
    $responsePayload = [
        'message' => 'Login successful!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'profile_picture' => $user->profile_picture,
            // Add more fields if needed
        ],
    ];

    return response()->json($responsePayload, 200);
}


public function requestPasswordReset(Request $request)
{
    // Validate the request data
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    // Send the password reset link to the user's email
    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => 'Password reset link sent.'], 200)
        : response()->json(['message' => 'Failed to send reset link.'], 500);
}


public function resetPassword(Request $request)
{
    // Validate the request data
    $request->validate([
        'token' => 'required',
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Attempt to reset the password
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => 'Password has been reset successfully.'], 200)
        : response()->json(['message' => 'Failed to reset password.'], 500);
}

// Fetch Chef Profile Data
public function getChefProfile($id, Request $request)
{
    $apiKey = $request->header('X-API-Key');
    $expectedApiKey = 'ABDI'; // Replace with your actual unique key

    if ($apiKey !== $expectedApiKey) {
        return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
    }

    // Fetch the chef's profile data
    $chef = User::with('recipes', 'votes', 'events')->find($id);

    if (!$chef) {
        return response()->json(['message' => 'Chef not found.'], 404);
    }

    // Prepare the response data
    $profileData = [
        'profile' => [
            'id' => $chef->id,
            'name' => $chef->name,
            'bio' => $chef->bio,
            'payment_status' => $chef->payment_status,
            'social_media_links' => $chef->social_media_links,
            'role' => $chef->role,
            'profile_picture' => $chef->profile_picture,
        ],
        'recipes' => $chef->recipes,
        'votes' => $chef->votes,
        'events' => $chef->events,
        'recipe_count' => $chef->recipes()->count(),
        'total_votes' => $chef->votes()->count(),
    ];

    return response()->json($profileData);
}


public function fetchUserProfile(Request $request,$id)
{
    $apiKey = $request->header('X-API-Key');
    $expectedApiKey = 'ABDI'; // Replace with your actual unique key

    if ($apiKey !== $expectedApiKey) {
        return response()->json(['message' => 'Unauthorized access. Invalid API Key.'], 401);
    }

    // Get the authenticated user
    $user = User::find($id); // Assuming you're using Laravel's built-in authentication

    // Load additional relationships if necessary
    $user->load('votes.recipe', 'events'); // Load votes and events relationships

    // Prepare the response data
    $responseData = [
        'id' => $user->id,
        'name' => $user->name,
        'username' => $user->username,
        'email' => $user->email,
        'status' => $user->status, // Assuming there's a status column
        'bio' => $user->bio, // Assuming there's a bio column
        'role' => $user->role, // Role (e.g., voter)
        'profile_picture' => $user->profile_picture, // Profile picture URL or path
        'social_media_links' => $user->social_media_links, // Assuming there's a field for social media links
        'recipes_voted_for' => $user->votes->map(function ($vote) {
            return [
                'recipe_id' => $vote->recipe_id,
                'recipe_title' => $vote->recipe->title, // Adjust according to your Recipe model
                // Add other recipe details as necessary
            ];
        }),
        'events_participated' => $user->events->map(function ($event) {
            return [
                'event_id' => $event->id,
                'event_name' => $event->name, // Adjust according to your Event model
                // Add other event details as necessary
            ];
        }),
    ];

    // Return the user's profile data
    return response()->json($responseData);
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
