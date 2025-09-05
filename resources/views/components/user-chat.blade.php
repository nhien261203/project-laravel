
{{-- Bong bóng chat nhỏ --}}
<div id="chatBubble" class="fixed bottom-5 right-5 w-16 h-16 bg-green-500 rounded-full shadow-lg flex items-center justify-center cursor-pointer z-50 hover:scale-110 transition-transform">
    <i class="fas fa-comment text-white text-xl"></i>
</div>

{{-- Khung chat popup --}}
<div id="chatPopup" class="fixed bottom-24 right-5 w-96 h-[500px] bg-white rounded-xl shadow-lg flex flex-col z-60 hidden">
    {{-- Header --}}
    <div class="flex justify-between items-center p-3 border-b border-gray-200">
        <div class="flex items-center gap-2">
            <img src="/images/ai-avatar.jpg" alt="NexusAI" class="w-8 h-8 rounded-full">
            <h3 class="font-semibold text-gray-800">Chat với trợ lý NexusAI</h3>
        </div>
        <button id="closeChat" class="text-gray-500 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Chat box --}}
    <div id="chatBoxPopup" class="flex-1 overflow-y-auto p-3 bg-gray-50 space-y-2">
        {{-- Tin nhắn --}}
    </div>

    {{-- Input --}}
    <div class="flex gap-2 p-3 border-t border-gray-200">
        <input id="chatInputPopup" type="text" placeholder="Nhập câu hỏi..."
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-green-300" />
        <button type="button" id="sendBtnPopup"
                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
            Gửi
        </button>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="/js/data.js"></script>
<script>
    //CSRF
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.content : '';

    //DOM
    const chatBubble = document.getElementById("chatBubble");
    const chatPopup = document.getElementById("chatPopup");
    const closeChat = document.getElementById("closeChat");
    const chatBox = document.getElementById("chatBoxPopup");
    const input = document.getElementById("chatInputPopup");
    const sendBtn = document.getElementById("sendBtnPopup");

    const AI_AVATAR = '/images/ai-avatar.jpg'; // đường dẫn avatar AI


    // XSS escape
    function escapeHtml(text) {
        const div = document.createElement("div");
        div.innerText = text;
        return div.innerHTML;
    }

    //Scroll
    function scrollToBottom() {
        chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: "smooth" });
    }

    //Thêm tin nhắn
    function appendMessage(message, from = "user") {
        let align, bg, avatarHtml = '';

        if (from === "user") {
            align = "justify-end";
            bg = "bg-green-100";
        } else {
            align = "justify-start";
            bg = "bg-gray-200";

            // Thêm avatar AI
            avatarHtml = `
                <img src="${AI_AVATAR}" alt="AI" class="w-8 h-8 rounded-full mr-2 flex-shrink-0">
            `;
        }

        chatBox.insertAdjacentHTML("beforeend", `
            <div class="flex ${align} items-end mb-1">
                ${avatarHtml}
                <span class="${bg} px-3 py-2 rounded-2xl inline-block max-w-[80%] break-words">
                    ${escapeHtml(message)}
                </span>
            </div>
        `);

        scrollToBottom();
    }


    //Hiển thị "Đang suy nghĩ"
    function showThinking() {
        const id = "thinking-" + Date.now();
        chatBox.insertAdjacentHTML("beforeend", `
            <div id="${id}" class="flex justify-start items-end mb-1">
                <img src="${AI_AVATAR}" alt="AI" class="w-8 h-8 rounded-full mr-2 flex-shrink-0">
                <div class="typing bg-gray-200 px-3 py-2 rounded-2xl">
                    Đang suy nghĩ <span></span><span></span><span></span>
                </div>
            </div>
        `);
        scrollToBottom();
        return id;
    }


    //Load lịch sử từ DB
    async function loadHistory() {
        try {
            const res = await fetch("/chatbot/history");
            const history = await res.json();
            chatBox.innerHTML = "";
            history.forEach(msg => appendMessage(msg.message, msg.sender));
        } catch (err) {
            console.error("Không load được lịch sử:", err);
        }
    }

    //Gửi tin nhắn
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
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    message: userMessage,
                    productData: window.PRODUCT_DATA || null
                })
            });

            if (!res.ok) throw new Error("Server lỗi " + res.status);

            const data = await res.json();
            document.getElementById(thinkingId)?.remove();
            appendMessage(data.answer, "bot");

        } catch (err) {
            document.getElementById(thinkingId)?.remove();
            appendMessage("Lỗi: " + err.message, "bot");
        }
    }

    //Event
    chatBubble.addEventListener("click", () => {
        chatPopup.classList.remove("hidden");
        chatBubble.classList.add("hidden");
        loadHistory();
    });

    closeChat.addEventListener("click", () => {
        chatPopup.classList.add("hidden");
        chatBubble.classList.remove("hidden");
    });

    input.addEventListener("keypress", e => { if(e.key==="Enter") sendMessage(); });
    sendBtn.addEventListener("click", sendMessage);

    document.addEventListener("keydown", e => {
        if(e.key === "Escape" && !chatPopup.classList.contains("hidden")){
            chatPopup.classList.add("hidden");
            chatBubble.classList.remove("hidden");
        }
    });
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
