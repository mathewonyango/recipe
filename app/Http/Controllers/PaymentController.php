<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;


class PaymentController extends Controller
{
    //

     // Update or create a payment record for a user
     public function updatePayment(Request $request)
{
    // Validate input
    $request->validate([
        'amount' => 'required|numeric',
        'status' => 'required|in:paid,unpaid,partial',
        'transaction_id' => 'nullable|string',
    ]);

    // Find the user
    $user = User::findOrFail($request->user_id);

    // Check if the user has an existing payment record
    $payment = Payment::where('user_id', $request->user_id)->first();

    if ($payment) {
        // If the user has a payment record, accumulate the amount
        $newAmount = $payment->amount + $request->input('amount');

        // Update the payment record
        $payment->update([
            'amount' => $newAmount,
            'status' => $this->determinePaymentStatus($newAmount), // Function to determine status based on amount
            'transaction_id' => $request->input('transaction_id'),
        ]);
    } else {
        // If no payment record exists, create a new one
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $request->input('amount'),
            'status' => $this->determinePaymentStatus($request->input('amount')), // Set initial status
            'transaction_id' => $request->input('transaction_id'),
        ]);
    }

    return response()->json([
        'message' => 'Payment updated successfully',
        'payment' => $payment
    ], 200);
}

/**
 * Determine the payment status based on the total amount paid.
 */
protected function determinePaymentStatus($amount)
{
    $totalAmountRequired = 1000; // Define the total amount required

    if ($amount >= $totalAmountRequired) {
        return 'paid';
    } elseif ($amount > 0 && $amount < $totalAmountRequired) {
        return 'partial';
    } else {
        return 'unpaid';
    }
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
