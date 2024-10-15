<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password; // Include this at the top

use Illuminate\Support\Facades\DB; // For database operations
use Illuminate\Support\Str; // For generating the random token
use Illuminate\Support\Carbon; // For handling time and token expiration
//Comment
use App\models\Comment;
use App\Models\Vote;
use App\Models\Event;

class AuthController extends Controller
{
    //

    public function showLoginForm()
    {
        return view('auth.login'); // Create this view
    }


    public function appLogin(Request $request)
    {
        try {
        // Check if the API key is provided and matches
        $apiKey = $request->input('api_key');
        $expectedApiKey = env('API_KEY');

        if ($apiKey !== $expectedApiKey) {
            return response()->json([
                'response' => "401",
                'status' => 'error',
                'response_description' => 'Unauthorized access. Invalid API Key.'
            ], 401);
        }

        // Validate the request data (email or username + password)
        $validator = Validator::make($request->all(), [
            'email' => 'required|string', // The 'name' field is used to hold name, email, or username
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => "900",
                'response_description' => 'Validation failed. Name/Email/username and password are required.',
                'errors' => $validator->errors()
            ], 400);
        }

        // Check if the user exists by email, username, or name
        $user = User::where('email', $request->email)
        ->orWhere('username', $request->email)
        ->orWhere('name', $request->email)
        ->first();

        // If the user does not exist
        if (!$user) {
            return response()->json([
                'response' => "901",
                'response_description' => 'Invalid email, username, or name. The user was not found.'
            ], 404);
        }

        // If the user exists but the password is incorrect
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'response' => "999",
                'response_description' => 'Incorrect password. Please try again.'
            ], 401);
        }
                // Get today's date
                $today = Carbon::now()->toDateString();

                // Fetch active events (event date is today or in the future)
                $activeEvents = Event::where('day_of_event', '>=', $today)->get();

                // Fetch past events (event date is in the past)
                $pastEvents = Event::where('day_of_event', '<', $today)->get();

                // Prepare response payload
                $responsePayload = [
                    'response'=>"000",
                    'status' => 'success',
                    'response_description' => 'Login successful!',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'profile_picture' => $user->profile_picture ?? '',

                        // 'username' => $user->username,
                        'email' => $user->email,
                        'role' => $user->role,
                        'payments'=>$user->payment,
                        'recipes' => [],
                        'events' => [
                            'active' => [],
                            'past' => [],
                        ],
                        'totals' => [
                            'total_votes_earned' => 0,
                            'total_views_earned' => 0,
                            'total_comments_received' => 0,
                        ],
                    ],
                ];

                // Include active events
                foreach ($activeEvents as $event) {
                    $eventData = [
                        'status'=>"active",
                        'id' => $event->id,
                        'Event_name' => $event->name,
                        'Event_charges' => $event->charges,
                        'Event_date' => $event->day_of_event,
                        'Event_location' => $event->location,
                        "Event_topic"=>$event->topic->name,
                        "Event_time"=>$event->time,
                        'Event_contact_number'=>$event->contact_number,
                    ];
                    $responsePayload['user']['events']['active'][] = $eventData;
                }

                // Include past events
                foreach ($pastEvents as $event) {
                    $eventData = [
                        'status'=>"closed",
                        'id' => $event->id,
                        'Event_name' => $event->name,
                        'Event_charges' => $event->charges,
                        'Event_date' => $event->day_of_event,
                        'Event_location' => $event->location,
                        "Event_topic"=>$event->topic->name,
                        "Event_time"=>$event->time,
                        'Event_contact_number'=>$event->contact_number,
                    ];
                    $responsePayload['user']['events']['past'][] = $eventData;
                }

                // If user is a chef, include their recipes and other chef-specific data
                if ($user->role === 'chef') {
                    // Fetch the chef's own recipes
                    $chefRecipes = Recipe::where('user_id', $user->id)
                                          ->withCount('votes') // Include vote count
                                          ->get();

                    // Initialize totals for the chef
                    $totalVotesChef = 0;
                    $totalViewsChef = 0;
                    $totalCommentsChef = 0;

                    // Include chef-specific data in response
                    $responsePayload['user']['recipes'] = $chefRecipes->map(function ($recipe) use (&$totalVotesChef, &$totalViewsChef, &$totalCommentsChef) {
                        // Fetch total views and comments from Comment model
                        $totalViews = Comment::where('recipe_id', $recipe->id)
                                             ->where('interaction_type', 'view') // Assuming you have a 'type' field for 'view'
                                             ->count();

                        $totalComments = Comment::where('recipe_id', $recipe->id)
                                                ->where('interaction_type', 'comment') // Assuming you have a 'type' field for 'comment'
                                                ->count();

                        // Fetch the actual comments
                        $comments = Comment::where('recipe_id', $recipe->id)
                                           ->where('interaction_type', 'comment')
                                           ->get();

                        // Add up to totals for the chef
                        $totalVotesChef += $recipe->votes_count;
                        $totalViewsChef += $totalViews;
                        $totalCommentsChef += $totalComments;

                        // Return recipe-specific data with comments included
                        return [
                            'id' => $recipe->id,
                            'title' => $recipe->title,
                            'topic_name' => $recipe->topic->name,
                            'servings' => $recipe->servings,
                            'cook_time' => $recipe->cook_time,
                            "prepare_time" => $recipe->prep_time,
                            // 'cooking_time' => $recipe->cooking_time,
                            "ingredients" => $recipe->ingredients,
                            "instructions" => $recipe->instructions,
                            // 'user_id' => $recipe->user_id,
                            // 'status' => $recipe->status,
                            'image' => $recipe->image,
                            'tags' => $recipe->tags,
                            'difficulty_level' => $recipe->difficulty_level,
                            'nutritional_information' => $recipe->nutritional_information,
                            'total_votes' => $recipe->votes_count, // Total votes for this recipe
                            'total_views' => $totalViews,          // Total views for this recipe
                            'total_comments' => $totalComments,    // Total comments for this recipe
                            'comments' => $comments->map(function ($comment) {
                                return [
                                    'id' => $comment->id,
                                    'recipe_title' => $comment->recipe->title, // Include the recipe title here
                                    'user_id' => $comment->user_id,
                                    'comment_text' => $comment->comment,
                                    'created_at' => $comment->created_at,
                                ];
                            }),
                        ];
                    });

                    // Update totals for chef in the response
                    $responsePayload['user']['total_recipes_submitted'] = $chefRecipes->count();
                    $responsePayload['user']['totals']['total_votes_earned'] = $totalVotesChef;
                    $responsePayload['user']['totals']['total_views_earned'] = $totalViewsChef;
                    $responsePayload['user']['totals']['total_comments_received'] = $totalCommentsChef;

                } else {
                    // For normal users, fetch all recipes and indicate the ones they've voted for
                    $allRecipes = Recipe::withCount('votes')
                                        ->with('user') // Include user relationship for chef details
                                        ->get();

                    $votedRecipes = Vote::where('user_id', $user->id)->pluck('recipe_id')->toArray();

                    $responsePayload['user']['recipes'] = $allRecipes->map(function ($recipe) use ($votedRecipes) {
                        return [
                           'id' => $recipe->id,
                            'title' => $recipe->title,
                            'topic_name' => $recipe->topic->name,
                            'servings' => $recipe->servings,
                            'cook_time' => $recipe->cook_time,
                            "prepare_time" => $recipe->prep_time,
                            "ingredients" => $recipe->ingredients,
                            "instructions" => $recipe->instructions,
                            // 'user_id' => $recipe->user_id,
                            // 'status' => $recipe->status,
                            'image' => $recipe->image,
                            'tags' => $recipe->tags,
                            'difficulty_level' => $recipe->difficulty_level,
                            'nutritional_information' => $recipe->nutritional_information,
                            'chef' => [
                                'id' => $recipe->user->id,
                                'name' => $recipe->user->name,
                                'profile_picture' => $recipe->user->profile_picture,
                            ],
                            'total_votes' => $recipe->votes_count, // Total votes for the recipe
                            'user_voted' => in_array($recipe->id, $votedRecipes), // Indicate if the user voted for this recipe
                        ];
                    });
                }

                return response()->json($responsePayload, 200);

            } catch (\Exception $e) {
                return response()->json([
                    'response'=>"999",
                    'response_description' => 'An error occurred: ' . $e->getMessage()], 500);
            }
        }

    public function portalLogin(Request $request)
    {
        // dd($request->all());
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Authentication passed
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register'); // Create this view
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['password'] = bcrypt($data['password']); // Hash the password
        User::create($data); // Create the user

        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }

    public function forgotPassword(Request $request)
    {

     $apiKey = $request->input('api_key'); // Use input() to get data from the body
     $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

     // Check if the provided API key matches the expected API key
     if ($apiKey !== $expectedApiKey) {
         return response()->json([
             'response'=>"401",
             'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
     }
        // Validate the email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
             'respose' => "901",
                'response_description' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Generate reset token
        $token = Str::random(60);

        // Store token in password_resets table
       $passwordReset = DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        if(!$passwordReset) {
            return response()->json([
             'response' => "999",
                'response_description' => 'An error occurred while generating the password reset token. Please try again later.',
            ], 500);
        }

        // Send token back to the app (this can also be emailed)
        $user=User::where('email', $request->email)->first();
        return response()->json([
             'response' => "000",
            'response_description' => 'Password reset token generated.',
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email,

        ], 200);
    }

     // Reset password method
     public function resetPassword(Request $request)
     {


         $apiKey = $request->input('api_key'); // Use input() to get data from the body
         $expectedApiKey = env('API_KEY'); // Fetch the expected API key from the environment

         // Check if the provided API key matches the expected API key
         if ($apiKey !== $expectedApiKey) {
             return response()->json([
                 'response'=>"401",
                 'response_description' => 'Unauthorized access. Invalid API Key.'], 401);
         }
         // Validate the token, email, and new password
         $validator = Validator::make($request->all(), [
             'token' => 'required',
             'email' => 'required|email|exists:users,email',
             'password' => 'required|string|confirmed|min:6',
         ]);

         if ($validator->fails()) {
             return response()->json([
                 'response'=>"902",
                 'response_description' => 'Validation failed',
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
                 'response' => "999",
                 'response_description' => 'Invalid token or email.'
             ], 400);
         }

         // Check if the token is expired (valid for 1 hour)
         $tokenExpired = Carbon::parse($reset->created_at)->addHour()->isPast();
         if ($tokenExpired) {
             return response()->json([
                 'response' => "901",
                 'response_description' => 'Token has expired.',
             ], 400);
         }

         // Reset the user's password
         $user = User::where('email', $request->email)->first();
         $user->password = Hash::make($request->password);
         $user->save();

         // Delete the token after a successful password reset
         DB::table('password_resets')->where('email', $request->email)->delete();

         return response()->json([
             'response' => "000",
             'response_description' => 'Password has been reset successfully.',
             'user' => $user,
             'new-password' => $request->password
         ], 200);
     }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
