<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatBotController extends Controller
{
    public function ask(Request $request)
    {
        $userMessage = $request->input('message');
        $productData = $request->input('productData');

        // Xác định user_id hoặc session_id
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = $request->session()->getId();

        // Lưu tin nhắn user vào DB
        Message::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'sender' => 'user',
            'message' => $userMessage,
        ]);

        // Prompt cho AI
        $prompt = "Thông tin sản phẩm:\n" . json_encode($productData, JSON_UNESCAPED_UNICODE) . 
        "\n\nCâu hỏi của khách hàng: " . $userMessage;


        // Gọi API Gemini
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(config('services.gemini.url') . '?key=' . config('services.gemini.key'), [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        $data = $response->json();
        $answer = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Xin lỗi, tôi chưa hiểu.";

        // Lưu tin nhắn bot vào DB
        Message::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'sender' => 'bot',
            'message' => $answer,
        ]);

        return response()->json([
            'answer' => $answer,
            'raw' => $data
        ]);
    }

    // API: lấy lịch sử tin nhắn
    public function history(Request $request)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = $request->session()->getId();

        $messages = Message::where(function ($q) use ($userId, $sessionId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('session_id', $sessionId);
            }
        })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }
    public function getDataForChatbotJson()
    {
        $products = Product::with([
            'brand',
            'category',
            'variants' => fn($q) => $q->where('status', 1)
        ])->where('status', 1)->get();

        $formatted = $products->map(function ($p) {
            return [
                'product_name' => $p->name,
                'brand' => $p->brand->name ?? null,
                'category' => $p->category->name ?? null,
                'variants' => $p->variants->map(function ($v) {
                    $original = $v->original_price ?? 0;
                    $salePercent = $v->sale_percent ?? 0;
                    $salePrice = $salePercent > 0
                        ? $original - ($original * $salePercent / 100)
                        : $original;

                    return [
                        'color' => $v->color,
                        'ram' => $v->ram,
                        'storage' => $v->storage,
                        'chip' => $v->chip,
                        'original_price' => $original,
                        'sale_percent' => $salePercent,
                        'final_price' => $salePrice,
                    ];
                })
            ];
        });

        return response()->json([
            'message' => 'Dữ liệu cho chatbot',
            'products' => $formatted
        ]);
    }
}
