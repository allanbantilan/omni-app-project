<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Http\Request;

class PusherController extends Controller
{
    public function index()
    {
        return view('chat.chat');
    }
    public function broadcast(Request $request)
    {
        // Store the uploaded file if it exists
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public'); // Store in the 'public' disk
        }

        $message = $request->get('message');
        $data = [
            'message' => $message,
            'attachment' => $attachmentPath
        ];

        broadcast(new PusherBroadcast($data))->toOthers();

        // Return the view with both the message and attachment
        return view('chat.broadcast', ['message' => $message, 'attachment' => $attachmentPath]);
    }
    public function recieve(Request $request)
    {
        return view('chat.recieve', [
            // 'message' => $request->get('message'),
            // 'attachment' => $request->get('attachment')
            'message' => $request->input('message'),
            'attachment' => $request->input('attachment')
        ]);
    }
}
