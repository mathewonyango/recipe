<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PesapalController extends Controller
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $baseUrl;
    protected $notificationId;

    public function __construct()
    {
        $this->consumerKey = env('PESAPAL_CONSUMER_KEY');
        $this->consumerSecret = env('PESAPAL_CONSUMER_SECRET');
        $this->baseUrl = env('PESAPAL_BASE_URL');
        $this->notificationId = env('PESAPAL_NOTIFICATION_ID');
    }

    public function submitOrder(Request $request)
    {

        $uniqueOrderId = uniqid('order-', true); // Or use Str::uuid()

        // Sample Data - You may replace with actual inputs from your form or database
        // $bearerToken = 'your_bearer_token'; // Replace with your generated bearer token

            $tokenResponse = $this->getAccessToken();
           $bearerToken = $tokenResponse['token']; // Access the token from the returned array

            // Save the transaction details to the database first
            $transaction = new Transaction();
            $transaction->order_id = $uniqueOrderId;
            $transaction->phone_number = $request->phone_number;
            $transaction->amount = $request->amount;
            $transaction->description = 'Payment description goes here';
            $transaction->status = 'pending'; // Initial status is pending
            $transaction->save(); // Save the transaction to the database


        // Payment request payload
        $payload = [
            'id' => $uniqueOrderId, // Unique ID for each order
            'currency' => 'KES',
            'amount' =>$request->amount,
            'description' => 'Payment description goes here',
            'callback_url' => 'https://2m75wiwmfc.sharedwithexpose.com/payment/callback', // Your callback URL
            'notification_id' =>env('PESAPAL_IPN_ID'),
            'branch' => 'Store Name - HQ',
            'billing_address' => [
                'email_address' => 'mathewsagumbah@gmail.com',
                'phone_number' => '0702622569',
                'country_code' => 'KE',
                'first_name' => 'Mathews',
                'last_name' => 'Onyango',
                'line_1' => 'Pesapal Limited',
                'city' => '',
                'state' => '',
                'postal_code' => '',
                'zip_code' => ''
            ]
        ];

        // Make the POST request to Pesapal SubmitOrderRequest API
        $response = Http::withToken($bearerToken)
            ->accept('application/json')
            ->contentType('application/json')
            ->post('https://cybqa.pesapal.com/pesapalv3/api/Transactions/SubmitOrderRequest', $payload);

        // Handle response
        if ($response->successful()) {
            $data = $response->json();
            return response()->json([
                'message' => 'Payment order created successfully',
                'data' => $data
            ]);
        }

        // If there's an error, return the error message
        return response()->json([
            'error' => 'Failed to create payment order',
            'message' => $response->body()
        ], $response->status());
    }

    public function handleCallback(Request $request)
    {
        // Assuming Pesapal sends 'orderId' and 'status' in the callback URL
        $status = $request->query('status');
        // $transactionId = $request->query('transactionId');
        $orderId = $request->query('orderId'); // Assuming this is passed

        // Find the transaction by order ID
        $transaction = Transaction::where('order_id', $orderId)->first();

        if ($transaction) {
            // Update the transaction details
            $transaction->status = $status; // Update the status based on the callback
            // $transaction->pesapal_transaction_id = $transactionId;
            $transaction->updated_at = now(); // Set the updated timestamp
            $transaction->save(); // Save changes to the database

            return response()->json(['message' => 'Transaction updated successfully']);
        } else {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    }


    public function getAccessToken()
    {
        try {
            // Set the sandbox URL
            $this->baseUrl = 'https://cybqa.pesapal.com/pesapalv3';

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/Auth/RequestToken', [
                'consumer_key' => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
            ]);

            if ($response->successful()) {
                return $response->json(); // Return the entire response
            }

            Log::error('Failed to get access token', ['response' => $response->body()]);
            throw new \Exception('Failed to get access token: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Exception while getting access token', ['error' => $e->getMessage()]);
            throw $e;
        }
    }


//     function registerIpnUrl(Request $request)
// {
//     // Set the base URL for sandbox or production
//     $baseUrl = 'https://cybqa.pesapal.com/pesapalv3/api/URLSetup/RegisterIPN'; // Sandbox URL
//     // $baseUrl = 'https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN'; // Production URL

//     // Replace with the token you received during authentication
//     $tokenResponse = $this->getAccessToken();
//     $token = $tokenResponse['token']; // Access the token from the returned array
//     // Make the POST request to register the IPN URL
//     $response = Http::withToken($token)
//         ->withHeaders([
//             'Accept' => 'application/json',
//             'Content-Type' => 'application/json',
//         ])
//         ->post($baseUrl, [
//             'url' => $request->url,
//             'ipn_notification_type' => $request->notificationType,
//         ]);

//     // Check for success or handle errors
//     if ($response->successful()) {
//         return $response->json(); // Returns the response as an associative array
//     } else {
//         // Handle the error response
//         return [
//             'error' => $response->json(),
//             'status' => $response->status(),
//         ];
//     }
// }



public function registerIpnUrl(Request $request)
    {
        $url = $request->input('url');
        $ipnNotificationType = $request->input('ipn_notification_type', 'GET');

        $payload = [
            'url' => $url,
            'ipn_notification_type' => $ipnNotificationType
        ];

        try {
            $response = Http::withToken("{$this->consumerKey}:{$this->consumerSecret}")
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->baseUrl}/api/URLSetup/RegisterIPN", $payload);

            Log::info('PesaPal API Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'message' => 'IPN registered successfully',
                    'ipn_id' => $data['ipn_id'] ?? null
                ]);
            } else {
                return response()->json([
                    'message' => 'Failed to register IPN',
                    'error' => $response->json(),
                    'status' => $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception in registerIPN', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'An error occurred while registering IPN',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
