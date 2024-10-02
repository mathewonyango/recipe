<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;

class PaymentController extends Controller
{
    // Update or create a payment record for a user
    public function updatePayment(Request $request)
    {
        // Validate input here
        $request->validate([
            'amount' => 'required|numeric',
            'transaction_id' => 'nullable|string',
            'user_id' => 'required|exists:users,id' // Ensure user_id is validated
        ]);

        // Find the user
        $user = User::findOrFail($request->user_id);

        // Check if the user has an existing payment record
        $payment = Payment::where('user_id', $request->user_id)->first();

        if ($payment) {
            // If the user has a payment record, accumulate the amount
            $newAmount = $payment->amount + $request->input('amount');

            // Update the payment record with the new amount
            $payment->update([
                'amount' => $newAmount,
                'transaction_id' => $request->input('transaction_id'),
            ]);
        } else {
            // If no payment record exists, create a new one
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $request->input('amount'),
                'transaction_id' => $request->input('transaction_id'),
            ]);
        }

        // Determine the total amount paid by the user
        $totalAmountPaid = Payment::where('user_id', $request->user_id)->sum('amount');

        // Determine the payment status based on the total amount paid
        $status = $this->determinePaymentStatus($totalAmountPaid);

        // Update the payment status in the current payment record
        $payment->update(['status' => $status]);

        return response()->json([
            'message' => 'Payment updated successfully',
            'payment' => $payment
        ], 200);
    }

    /**
     * Determine the payment status based on the total amount paid.
     */
    protected function determinePaymentStatus($totalAmountPaid)
    {
        $totalAmountRequired = 1000; // Define the total amount required

        if ($totalAmountPaid >= $totalAmountRequired) {
            return 'paid';
        } elseif ($totalAmountPaid > 0 && $totalAmountPaid < $totalAmountRequired) {
            return 'partial';
        } else {
            return 'unpaid';
        }
    }

    // Fetch payments for a user
    public function getPayments(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id' // Ensure user_id is validated
        ]);

        $user = User::findOrFail($request->user_id);

        $payments = $user->payments; // Fetch user's payments

        return response()->json([
            'message' => 'User payments fetched successfully',
            'payments' => $payments,
        ], 200);
    }
}
