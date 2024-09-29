<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use App\Http\Controllers\UsersController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\RecipesController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ChefsController;
use App\Http\Controllers\VotesController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
// LogViewerController
use App\Http\Controllers\LogViewerController;
use App\Http\Controllers\DeploymentController;
use OpenApi\Annotations as OA;





Route::get('/api/csrf-token', function () {
    return Response::json(['csrf_token' => csrf_token()]);
});

Route::get('/console', [LogViewerController::class, 'index']);
Route::get('/console/{fileName}', [LogViewerController::class, 'show']);
// In routes/web.php
Route::get('/deploy', [DeploymentController::class, 'index'])->name('deploy.index');
Route::post('/deploy', [DeploymentController::class, 'deploy'])->name('deploy.start');
Route::post('/revert', [DeploymentController::class, 'revert'])->name('deploy.revert');



Route::post('/vote', [VotesController::class, 'vote']);


Route::post('/register/user', [UsersController::class, 'register']);

Route::middleware('auth')->group(function () {

Route::get('/get-recipes', [RecipesController::class, 'index'])->name('recipes.index');
Route::post('/recipes', [RecipesController::class, 'addRecipe']);


//API
    // Route::get('/get/recipes', [RecipesController::class, 'getAllRecipes']);
    Route::get('/recipes/{id}', [RecipesController::class, 'getRecipeById']);
    Route::get('/recipe/approve/{id}', [RecipesController::class, 'approve'])->name('recipe.approve');
    Route::post('/recipe/toggle-status/{id}', [RecipesController::class, 'toggleStatus'])->name('recipe.toggleStatus');

    // Topic Routes
    Route::post('/topic', [TopicsController::class, 'addTopic'])->name('topics.store');
    Route::get('/topic/get', [TopicsController::class, 'index'])->name('topics.index');


    Route::get('/topics/create', [TopicsController::class, 'create'])->name('topics.create');

    Route::get('/topics/{id}', [TopicsController::class, 'getTopicById']);



    Route::get('/chef/all', [ChefsController::class, 'index'])->name('chefs.index');
    Route::get('/chef/pending', [ChefsController::class, 'pending'])->name('chefs.pending');
    // Route to approve a chef
    Route::get('/chef/approved', [ChefsController::class, 'approved'])->name('chefs.approved');

    Route::post('/chefs/approve/{id}', [UsersController::class, 'approveChef'])->name('chefs.approve');


    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Topic Management

Route::get('/profile/edit', [UsersController::class, 'edit'])->name('profile.edit');



// Profile settings route
Route::get('/settings/profile', [UsersController::class, 'edit'])->name('settings.profile');
Route::post('/settings/profile', [UsersController::class, 'update'])->name('settings.profile.update');

});



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



//APIs Goes Here
Route::post('/api/register-chef', [UsersController::class, 'registerChef'])->middleware('VerifyCsrfToken');
Route::post('/api/register', [UsersController::class, 'register'])->middleware('VerifyCsrfToken');

// Route::post('/api/chefs/login', [UsersController::class, 'loginChef']);
Route::post('/api/login', [UsersController::class, 'login']);

// Request Password Reset
Route::post('/api/users/password/reset/request', [UsersController::class, 'requestPasswordReset']);

// Reset Password
Route::post('/api/users/password/reset', [UsersController::class, 'resetPassword']);
//Auth::routes();

Route::get('/api/chefs', [UsersController::class, 'getChefs']);
Route::get('/api/chefs/{id}', [UsersController::class, 'getChefProfile']);
Route::post('/api/chefs/{id}', [UsersController::class, 'updateProfile']);

Route::get('/api/users', [UsersController::class, 'Users']);


// Route::get('/api/events/{topic}', [EventsController::class, 'fetchEvents']);
//Event
Route::get('/events', [EventsController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventsController::class, 'create'])->name('events.create');
Route::post('/events', [EventsController::class, 'store'])->name('events.store');
Route::get('/events/{event}', [EventsController::class, 'show'])->name('events.show');
Route::get('/events/{event}/edit', [EventsController::class, 'edit'])->name('events.edit');
Route::put('/events/{event}', [EventsController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [EventsController::class, 'destroy'])->name('events.destroy');
//Topics:


//Event
Route::get('/api/events/topic/{topicId}', [EventsController::class, 'getEventsByTopic']);
Route::get('/api/events', [EventsController::class, 'getAllEvents']);
Route::get('/api/event/', [EventsController::class, 'getEventById']);

Route::get('/api/topics', [TopicsController::class, 'getAllTopics']);
//Recipe

Route::get('/api/recipes', [RecipesController::class, 'getAllRecipes']);
Route::post('/api/recipes/add', [RecipesController::class, 'submitRecipe']);



Route::post('/api/vote/recipe', [VotesController::class, 'vote']);



Route::post('/api/forgot-password-token', [UsersController::class, 'forgotPassword']);
Route::post('/api/reset-password', [UsersController::class, 'resetPassword']);



// Route::prefix('api')->group(function () {

    // Submit a Comment
    Route::post('/api/recipe/comment', [RecipesController::class, 'submitComment'])
        ->name('recipes.submitComment');

    // Submit a Rating
    Route::post('/api/recipe/rate', [RecipesController::class, 'submitRating'])
        ->name('recipes.submitRating');

    // Log a View
    Route::post('/api/recipe/view', [RecipesController::class, 'RecordView'])
        ->name('recipes.logView');

    // Get All Interactions (views, ratings, comments)
    // Route::get('/recipes/{recipe_id}/interactions', [RecipesController::class, 'getInteractions'])
    //     ->name('recipes.getInteractions');

    // Get All Viewers for a Recipe
    // Route::get('/recipes/{recipe_id}/viewers', [RecipesController::class, 'getViewers'])
    //     ->name('recipes.getViewers');

    // // Get All Raters for a Recipe
    // Route::get('/recipes/{recipe_id}/raters', [RecipesController::class, 'getRaters'])
    //     ->name('recipes.getRaters');

    // // Get All Commenters for a Recipe
    // Route::get('/recipes/{recipe_id}/commenters', [RecipesController::class, 'getCommenters'])
    //     ->name('recipes.getCommenters');
// });
