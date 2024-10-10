<?php

namespace App\Http\Controllers;

use Twilio\Jwt\AccessToken;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
use Twilio\Jwt\Grants\VoiceGrant;

class CallNewController extends Controller
{
    public function index()
    {
        return view('call.call-new');
    }

    public function getAccessToken(Request $request)
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

    //handle inbound and outbound
    public function handleCallRouting(Request $request)
    {
        // Get the number we are calling.
        $dialledNumber = $request->get('To') ?? null;

        // Set up instance of voice response.
        $voiceResponse = new VoiceResponse();

        if ($dialledNumber != env('TWILIO_FROM_NUMBER')) {
            # Outbound phone call.

            // Remove any html special characters.
            $number = htmlspecialchars($dialledNumber);

            // Dial.
            $dial = $voiceResponse->dial('', ['callerId' => env('TWILIO_FROM_NUMBER')]);

            if (preg_match("/^[\d\-\(\) ]$/", $number)) {
                # Standard outbound phone call to telephone number.
                $dial->number($number);
            } else {
                # Client to client (Agent - Agent) phone call.
            }
        } elseif ($dialledNumber == env('TWILIO_FROM_NUMBER')) {
            # Inbound phone calls

            // Setup a dial / response.
            $dial = $voiceResponse->dial('');

            // Dial the client. (Hardcoded for now.)
            $dial->client('Mathew_James');
        } else {
            $voiceResponse->say("Thank you for calling up!");
        }

        return (string)$voiceResponse;
    }
}
