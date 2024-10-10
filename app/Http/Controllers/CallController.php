<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
use Twilio\Jwt\Grants\VoiceGrant;
use Illuminate\Support\Facades\Log;

class CallController extends Controller
{
    private $twilio;

    public function __construct()
    {
        // Initialize Twilio Client with credentials from environment variables
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->twilio = new Client($sid, $token);
    }

    public function index()
    {
        return view('call.call'); // Render the phone dialer view
    }


    public function makeCall(Request $request)
    {
        $request->validate(['phone_number' => 'required|string']);

        $toNumber = $request->input('phone_number');
        $fromNumber = env('TWILIO_FROM_NUMBER'); // Ensure this is set correctly in .env

        Log::info('Twilio SID: ' . env('TWILIO_SID'));
        Log::info('Twilio From Number: ' . $fromNumber);
        Log::info('Attempting to call: ' . $toNumber);

        try {
            // Make the call and include a message
            $call = $this->twilio->calls->create(
                $toNumber, // To number
                $fromNumber, // From number
                [
                    'twiml' => '<Response><Say>Hello! This is a call from  Twilio. Thank you for listening!. The call will end any moments now. </Say></Response>'
                ]
            );

            return response()->json(['message' => 'Call initiated successfully.', 'call_sid' => $call->sid]);
        } catch (\Exception $e) {
            Log::error('Error initiating call: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to initiate call.', 'error' => $e->getMessage()], 500);
        }
    }

    public function incomingCall(Request $request)
    {
        Log::info('Incoming call from: ' . $request->input('From'));

        return response('<Response><Say>Thank you for calling! Please hold while we connect you.</Say></Response>', 200)
            ->header('Content-Type', 'text/xml');
    }



    // public function makeCall(Request $request)
    // {
    //     $request->validate(['phone_number' => 'required|string']);

    //     $toNumber = $request->input('phone_number');
    //     $fromNumber = env('TWILIO_FROM_NUMBER'); // Ensure this is set correctly in .env

    //     Log::info('Twilio SID: ' . env('TWILIO_SID'));
    //     Log::info('Twilio From Number: ' . $fromNumber);
    //     Log::info('Attempting to call: ' . $toNumber);

    //     try {
    //         // Make the call
    //         $call = $this->twilio->calls->create(
    //             $toNumber, // To number
    //             $fromNumber, // From number
    //             ['url' => route('twimlResponse')] // URL for TwiML instructions
    //         );

    //         return response()->json(['message' => 'Call initiated successfully.', 'call_sid' => $call->sid]);
    //     } catch (\Exception $e) {
    //         Log::error('Error initiating call: ' . $e->getMessage());
    //         return response()->json(['message' => 'Failed to initiate call.', 'error' => $e->getMessage()], 500);
    //     }
    // }

    // public function twimlResponse()
    // {
    //     // Create a new TwiML response
    //     $response = new VoiceResponse();
    //     $response->say("Hello! This is a test call from Laravel and Twilio.");
    //     $response->say("Thank you for listening!");

    //     return response($response, 200)->header('Content-Type', 'text/xml');
    // }




















    // incoming calls 
    // public function callWebsite(Request $request)
    // {
    //     Log::info('Call received at callWebsite endpoint.');
    //     return response()->json(['message' => 'Call connected.']);
    // }
    public function generateToken(Request $request)
    {
        //get the access token from twilio via their package.
        $access_token = new AccessToken(
            env('TWILIO_SID'),
            env('TWILIO_API_KEY'),
            env('TWILIO_API_SECRET'),
            3600,
            'Allan_Bantilan',
            'us1'

        );

        //grant voice permission
        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid(env('TWILIO_SID'));

        //grant incoming calls permissions.
        $voiceGrant->setIncomingAllow(true);

        //Add grant to access token
        $access_token->addGrant($voiceGrant);

        //render out token to a JWT
        $token = $access_token->toJWT();

        return response()->json([
            'identity' => 'Allan_Bantilan',
            'token' => $token
        ]);
    }

    public function handleIncomingCall(Request $request)
    { {
            $response = new VoiceResponse();
            $response->say("Hello, you will recieve you call any minute now, bye."); // Simple voice response
            // Add a pause for 30 seconds
            $response->pause(['length' => 30]);

            return response($response)->header('Content-Type', 'text/xml');
        }
    }

    // public function handleIncomingCall(Request $request)
    // {
    //     Log::info('Incoming call received', $request->all());

    //     $twilioNumber = env('TWILIO_FROM_NUMBER');
    //     $yourPhoneNumber = "+639658621772";

    //     $response = new VoiceResponse();

    //     // First, dial your phone number
    //     $dial = $response->dial('');
    //     $dial->number($yourPhoneNumber);

    //     // If the call is answered, connect to the browser client
    //     // This should only happen if you want to connect to the browser client after answering.
    //     // You can use a 'action' URL to handle what happens after the call is answered.
    //     $dial->client('browser_client');

    //     // Add a simple message after connecting to the browser client
    //     $response->say("Please hold while we connect your call.");

    //     Log::info('TwiML Response: ' . $response);

    //     return response($response)->header('Content-Type', 'text/xml');
    // }
}
