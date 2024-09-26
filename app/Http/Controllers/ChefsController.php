<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Import the DB facade for raw queries



class ChefsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request) {
        // Handle chef registration
    }

    public function pending() {
        $chefs = User::where('role', 'chef')->where('approval_status', 'pending')->paginate(10);

        return view('Chef.pending', compact('chefs'));
        // Return chefs pending approval
    }

    public function approved() {
        // Approve the chef's registration
        $chefs = User::where('role', 'chef')->where('approval_status', 'approved')->paginate(10);

        // Prepare data for approval counts over time
        $chefs = User::where('role', 'chef')
        ->where('approval_status', 'approved')
        ->paginate(10);

    // Get the count of approved, pending, and rejected chefs
    $approvedCount = User::where('role', 'chef')
        ->where('approval_status', 'approved')
        ->count();

    $pendingCount = User::where('role', 'chef')
        ->where('approval_status', 'pending')
        ->count();

    $rejectedCount = User::where('role', 'chef')
        ->where('approval_status', 'rejected')
        ->count();


        $chefRecipeCounts = User::where('role', 'chef')
        ->where('approval_status', 'approved')
        ->withCount('recipes') // Assuming 'recipes' is the relation
        ->pluck('recipes_count', 'name');

    // Pass the counts and the chefs data to the view
    return view('Chef.approved', compact('chefs', 'approvedCount', 'pendingCount', 'rejectedCount','chefRecipeCounts'));

    }



    public function index() {
        // Return approved chefs
        $chefs = User::where('role', 'chef')->paginate(10);

        return view('Chef.index', compact('chefs'));
    }



}

