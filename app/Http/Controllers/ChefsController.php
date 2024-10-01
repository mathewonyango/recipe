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

    // Pass the counts and the chefs data to the view
    return view('Chef.approved', compact('chefs'));

    }



    public function index() {
        // Return approved chefs
        $chefs = User::where('role', 'chef')->latest()->paginate(10);

        return view('Chef.index', compact('chefs'));
    }

public function approve($id) {
    // Approve a chef
    $chef = User::findOrFail($id);
    $chef->update(['approval_status' => 'approved']);
    return redirect()->route('chefs.pending')->with('success', 'Chef approved successfully!');
}

}

