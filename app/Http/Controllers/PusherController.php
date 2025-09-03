<?php

namespace App\Http\Controllers;

use App\Events\NewConversationCreated;
use App\Events\PusherBroadcast;
use App\Models\Conversation;
use App\Models\MessageRealtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class PusherController extends Controller
{
    public function index()
    {
        return view('pusher'); 
    }

    // User gửi tin nhắn
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'client_id' => 'required|string', // Thêm validation cho client_id
        ]);

        $user = Auth::user();

        // Lấy hoặc tạo conversation mở
        $conversation = Conversation::where('user_id', $user->id)
            ->where('status', 'open')
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                
                'user_id' => $user->id,
                'status' => 'open',
            ]);
            
            event(new NewConversationCreated($conversation));
        }

        // Tạo message với conversation_id chắc chắn
        $message = MessageRealtime::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'sender' => 'user',
            'message' => $request->message,
            'client_id' => $request->client_id, // Lưu client_id từ request
        ]);

        // Broadcast realtime cho admin và các client khác
        broadcast(new PusherBroadcast($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }


    // Lấy tất cả tin nhắn của conversation
    public function getMessages($conversation_id)
    {
        $messages = MessageRealtime::with('user')
            ->where('conversation_id', $conversation_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    // Lấy conversation hiện tại của user + lịch sử tin nhắn
    // PusherController.php
    public function getLastMessage()
    {
        $user = Auth::user();

        // Lấy conversation mở gần nhất của user
        $conversation = Conversation::where('user_id', $user->id)
            ->where('status', 'open')
            ->first();

        if (!$conversation) {
            return response()->json(['conversation' => null, 'messages' => []]);
        }

        $messages = MessageRealtime::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    public function sendReply(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
            'client_id' => 'required',
        ]);

        $admin = Auth::user();

        // Check quyền bằng Spatie
        if (!$admin->hasAnyRole(['admin', 'staff'])) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $conversation = Conversation::findOrFail($request->conversation_id);

        // Nếu chưa có ai phụ trách thì gán staff/admin hiện tại
        if (!$conversation->assigned_admin_id) {
            $conversation->assigned_admin_id = $admin->id;
            $conversation->save();
        } else {
            // Nếu đã có admin/staff phụ trách
            if ($conversation->assigned_admin_id !== $admin->id && !$admin->hasRole('admin')) {
                return response()->json(['error' => 'This conversation is already assigned to another staff'], 403);
            }
        }

        // THÊM LOGIC NÀY VÀO ĐÂY
        // Đánh dấu tất cả tin nhắn từ 'user' là đã đọc khi admin trả lời
        MessageRealtime::where('conversation_id', $request->conversation_id)
            ->where('sender', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        // KẾT THÚC LOGIC MỚI

        $sender = $admin->hasRole('staff') ? 'staff' : 'admin';

        // Tạo message kèm client_id
        $message = MessageRealtime::create([
            'conversation_id' => $conversation->id,
            'user_id' => $admin->id,
            'sender' => $sender,
            'message' => $request->message,
            'read_at' => now(), // Cập nhật read_at cho tin nhắn của admin
            'client_id' => $request->client_id, // lưu client_id
        ]);

        // Broadcast cho các client khác
        broadcast(new PusherBroadcast($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    // Admin xem toàn bộ conversation + messages
    public function getConversations(Request $request)
    {
        $admin = Auth::user();

        if (!$admin->hasAnyRole(['admin', 'staff'])) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $query = Conversation::with(['user', 'admin'])
            ->whereHas('messages')
            ->orderBy('updated_at', 'desc');

        if ($admin->hasRole('staff')) {
            $query->where('assigned_admin_id', $admin->id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $conversations = $query->get();

        // Đếm số tin nhắn chưa đọc cho mỗi conversation
        $conversations->each(function ($conversation) {
            $unreadCount = $conversation->messages()
                ->where('sender', 'user')
                ->whereNull('read_at')
                ->count();
            $conversation->unread_count = $unreadCount;
        });

        return response()->json($conversations);
    }
    public function adminIndex()
    {
        return view('admin.chat'); // Blade riêng cho admin quản lý chat
    }
}
// luu y: phải đổi queue thành sync trong .env để chạy realtime ngay lập tức
// QUEUE_CONNECTION=sync
