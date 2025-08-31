<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use Illuminate\Http\Request;

class PusherController extends Controller
{
    public function index()
    {
        return view('pusher'); // view chính vẫn giữ
    }

    // API gửi tin nhắn
    public function broadcast(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = $request->get('message');

        // Broadcast lên Pusher
        broadcast(new PusherBroadcast($message))->toOthers();

        // Trả về JSON để frontend render
        return response()->json([
            'message' => $message,
            'type' => 'broadcast' // loại tin nhắn
        ]);
    }

    // API nhận tin nhắn (Pusher bind)
    public function receive(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        return response()->json([
            'message' => $request->message,
            'type' => 'receive'
        ]);
    }
}
