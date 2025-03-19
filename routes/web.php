<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    UsersController, DashboardController, TopicsController, RecipesController, EventsController,
    ReportsController, ChefsController, VotesController, AuthController, LogViewerController,
    DeploymentController, BackupController,PaymentController,FeedbackController,PesapalController
};
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\VerifyCsrfToken;

// CSRF Token Route
Route::get('/api/csrf-token', function () {
    return Response::json(['csrf_token' => csrf_token()]);
});

// Backup Routes
Route::get('/backup/trigger', [BackupController::class, 'triggerBackup'])->name('trigger-backup');
Route::get('/backups', [BackupController::class, 'viewBackups'])->name('view-backups');

// Log Viewer Routes
Route::get('/console', [LogViewerController::class, 'index']);
Route::get('/console/{fileName}', [LogViewerController::class, 'show']);

// Deployment Routes
Route::get('/deploy', [DeploymentController::class, 'index'])->name('deploy.index');
Route::post('/deploy', [DeploymentController::class, 'deploy'])->name('deploy.start');
Route::post('/revert', [DeploymentController::class, 'revert'])->name('deploy.revert');

// Vote Route
Route::post('/vote', [VotesController::class, 'vote']);

// User Registration Route
Route::post('/register/user', [UsersController::class, 'register']);

// Authenticated Routes Group
Route::middleware('auth')->group(function () {

    // Dashboard Routes
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports.index');

    // Recipe Routes
    Route::get('/get-recipes', [RecipesController::class, 'index'])->name('recipes.index');
    Route::post('/recipes', [RecipesController::class, 'addRecipe']);
    Route::get('/recipes/{id}', [RecipesController::class, 'getRecipeById']);
    Route::get('/recipe/approve/{id}', [RecipesController::class, 'approve'])->name('recipe.approve');
    Route::post('/recipe/toggle-status/{id}', [RecipesController::class, 'toggleStatus'])->name('recipe.toggleStatus');

    // Topic Routes
    Route::post('/topic', [TopicsController::class, 'addTopic'])->name('topics.store');
    Route::get('/topic/get', [TopicsController::class, 'index'])->name('topics.index');
    Route::get('/topics/create', [TopicsController::class, 'create'])->name('topics.create');
    Route::get('/topics/{id}', [TopicsController::class, 'getTopicById']);

    // Chef Routes
    Route::get('/chef/all', [ChefsController::class, 'index'])->name('chefs.index');
    Route::get('/chef/pending', [ChefsController::class, 'pending'])->name('chefs.pending');
    Route::get('/chef/approved', [ChefsController::class, 'approved'])->name('chefs.approved');
    Route::post('/chefs/approve/{id}', [UsersController::class, 'approveChef'])->name('chefs.approve');

    // Profile Routes
    Route::get('/profile/edit', [UsersController::class, 'edit'])->name('profile.edit');
    Route::get('/settings/profile', [UsersController::class, 'edit'])->name('settings.profile');
    Route::post('/settings/profile', [UsersController::class, 'update'])->name('settings.profile.update');
});


// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'portalLogin']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// API Routes
    Route::post('/api/register-chef', [UsersController::class, 'registerChef']);
    Route::post('/api/register', [UsersController::class, 'register']);
    Route::post('/api/login', [AuthController::class, 'appLogin']);
    Route::post('/api/users/password/reset/request', [UsersController::class, 'requestPasswordReset']);
    Route::post('/api/users/password/reset', [UsersController::class, 'resetPassword']);
    Route::post('/api/forgot-password-token', [AuthController::class, 'forgotPassword']);
    Route::post('/api/reset-password', [AuthController::class, 'resetPassword']);

    // Chef API Routes
    Route::get('/api/chefs', [UsersController::class, 'getChefs']);
    Route::get('/api/chefs/{id}', [UsersController::class, 'getChefProfile']);
    Route::post('/api/chefs/{id}', [UsersController::class, 'updateProfile']);
    Route::post('/api/user/update', [UsersController::class, 'updateUser']);
    Route::post('/api/chef/update', [UsersController::class, 'updateChef']);



    // User API Route
    Route::get('/api/users', [UsersController::class, 'fetchUsers']);
    Route::get('/api/chefs', [UsersController::class, 'fetchChefs']);


    // Event API Routes
    Route::get('/api/events/topic/{topicId}', [EventsController::class, 'getEventsByTopic']);
    Route::get('/api/events', [EventsController::class, 'getAllEvents']);
    Route::get('/api/event/', [EventsController::class, 'getEventById']);
    Route::post('/api/events', [EventsController::class, 'store']);

    // Topic API Route
    Route::get('/api/topics', [TopicsController::class, 'getAllTopics']);

    // Recipe API Routes
    Route::get('/api/recipes', [RecipesController::class, 'getAllRecipes']);
    Route::post('/api/recipes/add', [RecipesController::class, 'submitRecipe']);
    Route::post('/api/recipe/comment', [RecipesController::class, 'submitComment'])->name('recipes.submitComment');
    Route::post('/api/recipe/rate', [RecipesController::class, 'submitRating'])->name('recipes.submitRating');
    // Route::post(uri: '/api/recipe/views', [RecipesController::class, 'recipeViews'])->name('recipes.Views');
    //view
    Route::post('/api/recipe/view', [RecipesController::class, 'recipeView'])->name('recipes.View');

    // Vote API Route
    Route::post('/api/vote/recipe', [VotesController::class, 'vote']);

    Route::post('/api/payment/update', [PaymentController::class, 'updatePayment']);
    Route::get('/api/payment/user', [PaymentController::class, 'getPayments']);


// Event Routes
Route::get('/events', [EventsController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventsController::class, 'create'])->name('events.create');
Route::post('/events', [EventsController::class, 'store'])->name('events.store');
Route::get('/events/{event}', [EventsController::class, 'show'])->name('events.show');
Route::get('/events/{event}/edit', [EventsController::class, 'edit'])->name('events.edit');
Route::post('/events/{event}', [EventsController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [EventsController::class, 'destroy'])->name('events.destroy');


Route::post('/api/feedback', [FeedbackController::class, 'store']);
Route::get('/api/feedback/user/{userId}', [FeedbackController::class, 'getUserFeedback']);
Route::get('/api/feedback', [FeedbackController::class, 'getAllFeedback']);


    Route::post('/api/stk-push', [PesapalController::class, 'submitOrder']);
    Route::post('/api/callback', [PesapalController::class, 'callback'])->name('api.pesapal.callback');

    Route::post('/api/pesapal/access-token', [PesapalController::class, 'getAccessToken']);
//
Route::post('/api/pesapal/register-url', [PesapalController::class, 'registerIpnUrl']);
Route::get('/success', [PesapalController::class, 'checkPaymentStatus']);

Route::get('/payment/callback', [PesapalController::class, 'handleCallback'])->name('payment.callback');

Route::get('/api/get-all-comments', [RecipesController::class, 'fetchAllComments']);


//swageger
Route::get('/docs', function () {
    return view('swagger.index');
});
Route::get('/speed-test', function () {
    return view('speedtest.speedtest');
});


