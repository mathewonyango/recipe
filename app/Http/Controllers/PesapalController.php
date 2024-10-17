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

    public function initiateSTKPush(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'email' => 'nullable|email',
            'first_name' => 'nullable|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'zip_code' => 'nullable|string',
        ]);

        try {
            $token = $this->getAccessToken()['access_token']; // Get access token

            $transaction = Transaction::create([
                'order_id' => uniqid('ORDER_'),
                'phone_number' => $request->phone_number,
                'amount' => $request->amount,
                'description' => $request->description,
                'status' => 'pending',
            ]);

            $response = Http::withToken($token)
                ->post($this->baseUrl . '/api/Payments/RequestPayment', [
                    'id' => $transaction->order_id,
                    'currency' => 'KES',
                    'amount' => $request->amount,
                    'description' => $request->description,
                    'callback_url' => route('api.pesapal.callback'),
                    'notification_id' => $this->notificationId,
                    'billing_address' => [
                        'phone_number' => $request->phone_number,
                        'email_address' => $request->email ?? '',
                        'country_code' => 'KE',
                        'first_name' => $request->first_name ?? '',
                        'middle_name' => $request->middle_name ?? '',
                        'last_name' => $request->last_name ?? '',
                        'line_1' => $request->address_line_1 ?? '',
                        'line_2' => $request->address_line_2 ?? '',
                        'city' => $request->city ?? '',
                        'state' => $request->state ?? '',
                        'postal_code' => $request->postal_code ?? '',
                        'zip_code' => $request->zip_code ?? ''
                    ]
                ]);

            if ($response->successful()) {
                Log::info('STK push initiated successfully', ['response' => $response->json()]);
                return response()->json($response->json(), 200);
            }

            Log::error('Failed to initiate STK push', ['response' => $response->body()]);
            return response()->json(['error' => 'Failed to initiate STK push', 'message' => $response->body()], 500);
        } catch (\Exception $e) {
            Log::error('Exception while initiating STK push', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to initiate STK push', 'message' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        Log::info('Pesapal callback received', $request->all());

        try {
            $orderId = $request->input('OrderId');
            $status = $request->input('Status');
            $pesapalTransactionId = $request->input('PesapalTransactionId');

            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            $transaction->update([
                'status' => $status,
                'pesapal_transaction_id' => $pesapalTransactionId,
            ]);

            return response()->json(['status' => 'success', 'transaction' => $transaction]);
        } catch (\Exception $e) {
            Log::error('Failed to process callback', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to process callback', 'message' => $e->getMessage()], 500);
        }
    }

    public function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->post('https://pay.pesapal.com/v3/api/Auth/RequestToken');

            if ($response->successful()) {
                return response()->json(['access_token' => $response->json()['access_token']], 200);
            }

            Log::error('Failed to get access token', ['response' => $response->body()]);
            return response()->json(['error' => 'Failed to get access token', 'message' => $response->body()], 500);
        } catch (\Exception $e) {
            Log::error('Exception while getting access token', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to get access token', 'message' => $e->getMessage()], 500);
        }
    }
}
