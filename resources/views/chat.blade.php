@extends('layout.user')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="max-w-xl mx-auto my-6 border border-gray-300 rounded-xl p-4 bg-white shadow">
    <h2 class="text-xl font-semibold mb-4">AI ChatBot</h2>

    {{-- Khung chat --}}
    <div id="chatBox"
         class="h-96 overflow-y-auto border border-gray-200 p-3 mb-3 rounded-lg bg-gray-50 space-y-2">
        <!-- Tin nhắn sẽ hiển thị ở đây -->
    </div>

    {{-- Ô nhập + nút gửi --}}
    <div class="flex gap-2">
        <input id="chatInput" type="text" placeholder="Nhập câu hỏi..."
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-green-300" />

        <button type="button" id="sendBtn"
                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
            Gửi
        </button>
    </div>
</div>

{{-- Import dữ liệu sản phẩm --}}
<script src="/js/data.js"></script>
<script>
    // Escape text chống XSS
    function escapeHtml(text) {
        const div = document.createElement("div");
        div.innerText = text;
        return div.innerHTML;
    }

    const chatBox = document.getElementById("chatBox");
    const input = document.getElementById("chatInput");
    const sendBtn = document.getElementById("sendBtn");

    function scrollToBottom() {
        chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: "smooth" });
    }

    function appendMessage(message, from = "user") {
        const align = from === "user" ? "justify-end" : "justify-start";
        const bg = from === "user" ? "bg-green-100" : "bg-gray-200";

        chatBox.insertAdjacentHTML("beforeend", `
            <div class="flex ${align}">
                <span class="${bg} px-3 py-2 rounded-2xl inline-block max-w-[80%] break-words">
                    ${escapeHtml(message)}
                </span>
            </div>
        `);
        scrollToBottom();
    }

    function showThinking() {
        const id = "thinking-" + Date.now();
        chatBox.insertAdjacentHTML("beforeend", `
            <div id="${id}" class="flex justify-start">
                <div class="typing bg-gray-200 px-3 py-2 rounded-2xl">
                    Đang suy nghĩ <span></span><span></span><span></span>
                </div>
            </div>
        `);
        scrollToBottom();
        return id;
    }

    // Gửi tin nhắn
    async function sendMessage() {
        const userMessage = input.value.trim();
        if (!userMessage) return;

        appendMessage(userMessage, "user");
        input.value = "";

        const thinkingId = showThinking();

        try {
            const res = await fetch("/chatbot/ask", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: userMessage,
                    productData: window.PRODUCT_DATA
                })
            });

            if (!res.ok) throw new Error("Server lỗi " + res.status);

            const data = await res.json();
            document.getElementById(thinkingId)?.remove();

            //  Controller đã trả về answer
            appendMessage(data.answer, "bot");

        } catch (err) {
            document.getElementById(thinkingId)?.remove();
            appendMessage("⚠️ Lỗi: " + err.message, "bot");
        }
    }

    // Load lịch sử chat từ DB
    async function loadHistory() {
        try {
            const res = await fetch("/chatbot/history");
            const history = await res.json();

            history.forEach(msg => {
                appendMessage(msg.message, msg.sender);
            });
        } catch (err) {
            console.error("Không load được lịch sử:", err);
        }
    }

    // Event
    input.addEventListener("keypress", e => { if (e.key === "Enter") sendMessage(); });
    sendBtn.addEventListener("click", sendMessage);

    // Gọi khi load trang
    loadHistory();
</script>

<style>
.typing span {
   display:inline-block;
   width:6px;
   height:6px;
   margin:0 1px;
   background:#666;
   border-radius:50%;
   animation: blink 1.4s infinite both;
}
.typing span:nth-child(2) { animation-delay: 0.2s; }
.typing span:nth-child(3) { animation-delay: 0.4s; }

@keyframes blink {
  0% { opacity: .2; }
  20% { opacity: 1; }
  100% { opacity: .2; }
}
</style>
@endsection
