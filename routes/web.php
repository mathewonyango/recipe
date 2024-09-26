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




Route::get('/api/csrf-token', function () {
    return Response::json(['csrf_token' => csrf_token()]);
});




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
Route::post('/api/register-chef', [UsersController::class, 'registerChef']);
Route::post('/api/register', [UsersController::class, 'register']);

// Route::post('/api/chefs/login', [UsersController::class, 'loginChef']);
Route::post('/api/login', [UsersController::class, 'loginUser']);

// Request Password Reset
Route::post('/api/users/password/reset/request', [UsersController::class, 'requestPasswordReset']);

// Reset Password
Route::post('/api/users/password/reset', [UsersController::class, 'resetPassword']);
//Auth::routes();

Route::get('/api/chefs', [UsersController::class, 'getChefs']);
Route::get('/api/chefs/{id}', [UsersController::class, 'getChefProfile']);
Route::post('/api/chefs/{id}', [UsersController::class, 'updateProfile']);
//Route::get(uri: '/api/user/profile/{id}', [UsersController::class, 'fetchUserProfile']);




//Topics:

Route::get('/api/topics', [TopicsController::class, 'getAllTopics']);

Route::get('/api/recipes', [RecipesController::class, 'getAllRecipes']);
Route::post('/api/recipes/add', [RecipesController::class, 'submitRecipe']);


Route::post('/api/vote/recipes/{recipeId}', [VotesController::class, 'vote']);
