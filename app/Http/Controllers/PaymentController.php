<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use APP\Models\Payment;


class PaymentController extends Controller
{
    //

     // Update or create a payment record for a user
     public function updatePayment(Request $request)
{
    // Validate input
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'amount' => 'required|numeric',
        'transaction_id' => 'nullable|string',
    ]);

    // Set the required amount (in this case, Ksh 1000)
    $requiredAmount = 1000;

    // Determine the payment status based on the amount paid
    $status = 'unpaid'; // Default status

    if ($request->input('amount') >= $requiredAmount) {
        $status = 'paid'; // Fully paid if amount is equal or greater than 1000
    } elseif ($request->input('amount') > 0 && $request->input('amount') < $requiredAmount) {
        $status = 'partial'; // Partially paid if less than 1000
    }

    // Find the user
    $user = User::findOrFail($request->user_id);

    // Update or create payment for the user
    $payment = Payment::updateOrCreate(
        ['user_id' => $request->user_id],
        [
            'amount' => $request->input('amount'),
            'status' => $status, // Set status based on comparison
            'transaction_id' => $request->input('transaction_id'),
        ]
    );

    return response()->json([
        'message' => 'Payment updated successfully',
        'payment' => $payment,
    ], 200);
}


     // Fetch payments for a user
     public function getPayments(Request $request)
     {
         $user = User::findOrFail($request->user_id);

         $payments = $user->payments; // Fetch user's payments

         return response()->json([
             'message' => 'User payments fetched successfully',
             'payments' => $payments,
         ], 200);
     }
}
