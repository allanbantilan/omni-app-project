<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google\Service;
use App\Mail\SendEmail;
use Google\Service\Gmail;
use Illuminate\Http\Request;
use Google\Client as Google_Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailSendController extends Controller
{

    public function index()
    {
        return view('email.send-email');
    }

    public function sendEmail(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:2048', // Ensure attachment is optional and has a size limit
        ]);

        // Prepare the email data
        $emailData = [
            'subject' => $request->subject,
            'message' => $request->message,
            'attachment' => $request->file('attachment'), // This will be null if no file is uploaded
        ];

        // Create a new SendEmail instance with the prepared data
        $email = new SendEmail($emailData['subject'], $emailData['message'], $emailData['attachment']);

        // Send the email
        try {
            Mail::to($request->to)
                ->cc($request->cc) // Ensure CC is optional
                ->send($email);

            Log::info('Email sent successfully to: ' . $request->to);
            return back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to send email: ' . $e->getMessage()]);
        }
    }

    //recieving emails 

    public function fetchEmails()
    {
        Log::info('Starting to fetch emails.');

        $client = $this->getGoogleClient();

        // Check if user is authenticated
        if (!session('google_access_token')) {
            Log::info('User not authenticated. Redirecting to Google auth.');
            return redirect()->route('auth.google');
        }

        $client->setAccessToken(session('google_access_token'));

        // Check if access token is expired
        if ($client->isAccessTokenExpired()) {
            Log::warning('Access token has expired. Attempting to refresh.');
            if (!$this->refreshAccessToken($client)) {
                return redirect()->route('auth.google');
            }
        }

        try {
            $service = new Gmail($client);
            $user = 'me';
            $messages = $service->users_messages->listUsersMessages($user, ['maxResults' => 5]);

            $emails = [];
            foreach ($messages->getMessages() as $message) {
                $msg = $service->users_messages->get($user, $message->getId(), ['format' => 'full']);
                $email = $this->processEmail($msg, $service, $user);
                $emails[] = $email;
            }

            Log::info('Fetched emails successfully.', ['emails_count' => count($emails)]);
            return view('email.inbox',  ['emails' => $emails])->with('emails', $emails);
        } catch (\Exception $e) {
            Log::error('Error fetching emails: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch emails: ' . $e->getMessage()], 500);
        }
    }

    private function processEmail($msg, $service, $user)
    {
        $headers = $msg->getPayload()->getHeaders();
        $subject = $this->getHeader($headers, 'Subject');
        $from = $this->getHeader($headers, 'From');
        $date = $this->getHeader($headers, 'Date');
        $snippet = $msg->getSnippet();
        $threadId = $msg->getThreadId();

        $attachments = $this->getAttachments($msg, $service, $user);
        $replyChain = $this->getReplyChain($service, $user, $threadId);

        return [
            'subject' => $subject,
            'from' => $from,
            'snippet' => substr($snippet, 0, 100) . '...',
            'date' => Carbon::parse($date)->diffForHumans(),
            'attachments' => $attachments,
            'replyChain' => $replyChain,
        ];
    }

    private function getHeader($headers, $name)
    {
        foreach ($headers as $header) {
            if ($header->getName() === $name) {
                return $header->getValue();
            }
        }
        return '';
    }

    private function getAttachments($msg, $service, $user)
    {
        $attachments = [];
        if ($msg->getPayload()->getParts()) {
            foreach ($msg->getPayload()->getParts() as $part) {
                if (isset($part['filename']) && !empty($part['filename'])) {
                    try {
                        $attachmentId = $part['body']['attachmentId'];
                        $attachment = $service->users_messages_attachments->get($user, $msg->getId(), $attachmentId);
                        $attachments[] = [
                            'filename' => $part['filename'],
                            'data' => $attachment->getData(),
                        ];
                    } catch (\Exception $e) {
                        Log::error('Failed to retrieve attachment: ' . $e->getMessage());
                    }
                }
            }
        }
        return $attachments;
    }

    private function getReplyChain($service, $user, $threadId)
    {
        $thread = $service->users_threads->get($user, $threadId);
        $replyChain = [];

        foreach ($thread->getMessages() as $threadMessage) {
            $replyMsg = $service->users_messages->get($user, $threadMessage->getId(), ['format' => 'full']);

            // Extract attachments
            $attachments = [];
            if ($replyMsg->getPayload()->getParts()) {
                foreach ($replyMsg->getPayload()->getParts() as $part) {
                    if (isset($part['filename']) && !empty($part['filename'])) {
                        // Only process parts that are attachments
                        if (isset($part['body']['attachmentId'])) {
                            $attachmentId = $part['body']['attachmentId'];
                            try {
                                // Here's the fix: add the message ID as the second argument
                                $attachment = $service->users_messages_attachments->get($user, $threadMessage->getId(), $attachmentId);
                                // Store filename and base64 data
                                $attachments[] = [
                                    'filename' => $part['filename'],
                                    'data' => $attachment->getData(), // Base64 encoded data
                                ];
                            } catch (\Exception $e) {
                                Log::error('Failed to retrieve attachment: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }

            // Add reply details including attachments
            $replyChain[] = [
                'from' => $this->getHeader($replyMsg->getPayload()->getHeaders(), 'From'),
                'date' => $this->getHeader($replyMsg->getPayload()->getHeaders(), 'Date'), // Add date for display
                'snippet' => $replyMsg->getSnippet(),
                'attachments' => $attachments, // Include attachments in the reply chain
            ];
        }

        return $replyChain;
        // $thread = $service->users_threads->get($user, $threadId);
        // $replyChain = [];
        // foreach ($thread->getMessages() as $threadMessage) {
        //     $replyMsg = $service->users_messages->get($user, $threadMessage->getId());
        //     $replyChain[] = [
        //         'from' => $this->getHeader($replyMsg->getPayload()->getHeaders(), 'From'),
        //         'snippet' => $replyMsg->getSnippet(),
        //     ];
        // }
        // return $replyChain;
    }




    private function getGoogleClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Gmail Send and Recieve');
        $client->setScopes(Gmail::GMAIL_READONLY);
        $client->setAuthConfig(public_path('credentials.json'));
        $redirectUri = env('GOOGLE_REDIRECT_URI', 'http://127.0.0.1:8080/auth/callback');
        $client->setRedirectUri($redirectUri);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Debug: Print out the redirect URI
        Log::info('Redirect URI: ' . $redirectUri);

        return $client;
    }

    private function refreshAccessToken($client)
    {
        try {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            session(['google_access_token' => $client->getAccessToken()]);
            Log::info('Access token refreshed successfully.');
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to refresh access token: ' . $e->getMessage());
            return false;
        }
    }

    public function redirectToGoogle()
    {
        $client = $this->getGoogleClient();
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = $this->getGoogleClient();

        if ($request->has('code')) {
            try {
                $token = $client->fetchAccessTokenWithAuthCode($request->code);
                if (!array_key_exists('error', $token)) {
                    session(['google_access_token' => $token]);
                    Log::info('Access token saved to session.');
                    return redirect()->route('inbox');
                }
            } catch (\Exception $e) {
                Log::error('Exception during token exchange: ' . $e->getMessage());
            }
        }

        return redirect()->route('inbox')->with('error', 'Authentication failed.');
    }


    //google auth
    // public function redirectToGoogle()
    // {
    //     $client = new Google_Client();
    //     $client->setApplicationName('Your App Name');
    //     $client->setScopes(Gmail::GMAIL_READONLY);
    //     $client->setAuthConfig(public_path('credentials.json'));
    //     $client->setRedirectUri('http://127.0.0.1:8080/auth/callback');
    //     $client->setAccessType('offline');
    //     $client->setPrompt('select_account consent');

    //     // Generate the authorization URL
    //     $authUrl = $client->createAuthUrl();
    //     return redirect($authUrl);
    // }

    // public function handleGoogleCallback(Request $request)
    // {
    //     $client = new Google_Client();
    //     $client->setApplicationName('Gmail Send and Recieve');
    //     $client->setScopes(Gmail::GMAIL_READONLY);
    //     $client->setAuthConfig(public_path('credentials.json'));
    //     $client->setRedirectUri('http://127.0.0.1:8080/auth/callback');

    //     // Exchange authorization code for an access token
    //     // Check if the 'code' parameter is present in the request
    //     if ($request->has('code')) {
    //         // Exchange authorization code for an access token
    //         try {
    //             $token = $client->fetchAccessTokenWithAuthCode($request->code);

    //             // Log the received token for debugging
    //             Log::info('Access token received.', ['token' => $token]);

    //             // Check if there was an error fetching the token
    //             if (array_key_exists('error', $token)) {
    //                 Log::error('Error fetching access token: ' . $token['error']);
    //                 return response()->json(['error' => 'Failed to fetch access token.'], 500);
    //             }

    //             // Save the token for future use (e.g., in session or database)
    //             session(['google_access_token' => $token]);

    //             // Log that the token has been saved
    //             Log::info('Access token saved to session.');

    //             // Redirect to inbox or another route as needed
    //             return redirect()->route('inbox'); // Adjust as needed
    //         } catch (\Exception $e) {
    //             Log::error('Exception during token exchange: ' . $e->getMessage());
    //             return response()->json(['error' => 'Failed to exchange authorization code for access token.'], 500);
    //         }
    //     }

    //     return response()->json(['error' => 'Authorization failed, no code received.'], 400);
    // }
}
