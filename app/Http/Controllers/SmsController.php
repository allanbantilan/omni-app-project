<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    protected $messages = []; // Array to hold incoming messages

    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->get(); // Fetch messages from the database
        return view('Sms.send-sms', compact('messages')); // Pass messages to the view
    }

    public function sendSms(Request $request)
    {
        // Validate the input
        $validatedData = $request->validate([
            'to' => 'required|string|regex:/^\+?[1-9]\d{1,14}$/', // Ensure E.164 format
            'message' => 'required|string',
        ]);

        // Retrieve Twilio credentials from the environment
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilioNumber = env('TWILIO_FROM_NUMBER');

        Log::info("Sending SMS to: {$validatedData['to']}, Message: {$validatedData['message']}");
        Log::info('TWILIO_SID: ' . env('TWILIO_SID'));
        Log::info('TWILIO_AUTH_TOKEN: ' . env('TWILIO_AUTH_TOKEN'));
        try {
            // Initialize Twilio client
            $client = new Client($sid, $token);

            // Send SMS with status callback
            $message = $client->messages->create($validatedData['to'], [
                'from' => $twilioNumber,
                'body' => $validatedData['message'],
                'statusCallback' => route('sms.status') // Ensure this route is defined in your routes
            ]);

            Log::info("Message SID: {$message->sid}"); // Log the message SID for tracking

            return back()->with('success', 'Message sent successfully!')
                ->withInput(); // Keep input values
        } catch (\Twilio\Exceptions\RestException $e) {
            Log::error('Twilio error: ' . $e->getMessage()); // Log the error message
            return back()->withErrors(['error' => 'Twilio error: ' . $e->getMessage()])
                ->withInput(); // Keep input values
        } catch (\Exception $e) {
            Log::error('An error occurred: ' . $e->getMessage()); // Log any other errors
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                ->withInput(); // Keep input values
        }
    }
    public function receiveSms(Request $request)
    {
        // Capture incoming message details
        $from = $request->input('From'); // Sender's number
        $body = $request->input('Body'); // Message content

        // Log the incoming message for debugging
        Log::info("Received SMS from: $from, Message: $body");

        // Store the message in the database
        Message::create([
            'from' => $from,
            'body' => $body,
        ]);

        // Return a TwiML response (optional)
        return response('<Response></Response>', 200)
            ->header('Content-Type', 'text/xml');
    }

    public function showSendSmsForm()
    {
        $messages = Message::orderBy('created_at', 'desc')->get(); // Get all messages ordered by creation date
        return view('Sms.send-sms', compact('messages')); // Adjust the view name accordingly
    }
}
