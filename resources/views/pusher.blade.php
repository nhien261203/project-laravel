
<div id="userChatBubble" class="fixed bottom-24 right-5 w-16 h-16 bg-blue-500 rounded-full shadow-lg flex items-center justify-center cursor-pointer z-50 hover:scale-110 transition-transform">
    <i class="fas fa-comment-dots text-white text-xl"></i>
</div>

{{-- Chat popup --}}
<div id="userChatPopup" class="fixed bottom-24 right-5 w-96 h-[500px] bg-white rounded-xl shadow-lg flex flex-col z-50 hidden">

    {{-- Header --}}
    <div class="flex justify-between items-center p-3 border-b border-gray-200">
        <h3 class="font-semibold text-gray-800">Chat trực tuyến</h3>
        
        <button id="userCloseChat" class="text-gray-500 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Chat box --}}
    @if(Auth::check())
    {{-- Nếu người dùng đã đăng nhập, hiển thị giao diện chat --}}
    <div id="userChatBox" class="flex-1 overflow-y-auto p-3 bg-gray-50 space-y-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
        {{-- Tin nhắn append tại đây --}}
    </div>

    {{-- Input --}}
    <div class="flex gap-2 p-3 border-t border-gray-200">
        <input id="userChatInput" type="text" placeholder="Nhập tin nhắn..."
               class="flex-1 px-3 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400"/>
        <button type="button" id="userSendBtn"
                class="px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition">
            Gửi
        </button>
    </div>
    @else
    {{-- Nếu người dùng chưa đăng nhập, hiển thị thông báo --}}
    <div class="flex-1 flex flex-col items-center justify-center p-4 text-center">
        <p class="text-gray-600 mb-4">
            Vui lòng đăng nhập để bắt đầu trò chuyện với chúng tôi.
        </p>
        <a href="{{ route('login') }}" class="px-6 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition">
            Đăng nhập ngay
        </a>
    </div>
    @endif
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    const chatBubble = $("#userChatBubble");
    const chatPopup = $("#userChatPopup");
    const closeChat = $("#userCloseChat");
    const chatBox = $("#userChatBox");
    const input = $("#userChatInput");
    const sendBtn = $("#userSendBtn");

    // Toggle chat
    chatBubble.on("click", () => {
        chatPopup.removeClass("hidden");
        chatBubble.addClass("hidden");
    });
    closeChat.on("click", () => {
        chatPopup.addClass("hidden");
        chatBubble.removeClass("hidden");
    });

    // === Pusher Logic ===
    let conversationId = 0;
    let channel = null;
    const renderedClientIds = new Set();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true
    });

    function scrollToBottom() {
        chatBox[0].scrollTop = chatBox[0].scrollHeight;
    }

// Thêm biến global để lưu thời gian của tin nhắn cuối cùng được hiển thị
let lastMessageTime = null;

function appendMessage(message, sender = 'server', isError = false, clientId = null, timestamp = null) {
    // Xóa placeholder nếu còn
    const placeholder = $("#userChatBox .placeholder-message");
    if (placeholder.length) placeholder.remove();

    if (clientId && renderedClientIds.has(clientId)) return;
    if (clientId) renderedClientIds.add(clientId);

    const isSelf = sender === 'user' || sender === 'self';
    const align = isSelf ? 'justify-end' : 'justify-start';

    // Lấy thời gian hiện tại hoặc thời gian từ timestamp nếu có
    const currentMessageTime = timestamp ? new Date(timestamp) : new Date();

    // Định dạng giờ phút (ví dụ: 14:30)
    const formattedTime = currentMessageTime.toLocaleTimeString('vi-VN', {
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // So sánh với thời gian của tin nhắn cuối cùng để quyết định có hiển thị thời gian hay không
    const timeDifference = lastMessageTime ? (currentMessageTime - lastMessageTime) / 1000 : Infinity;

    // Lấy ngày hiện tại
    const today = new Date();
    const isSameDay = today.getDate() === currentMessageTime.getDate() &&
                      today.getMonth() === currentMessageTime.getMonth() &&
                      today.getFullYear() === currentMessageTime.getFullYear();

    // Chuỗi HTML cho tin nhắn
    let messageHtml = '';

    // Logic hiển thị ngày và giờ
    // Hiển thị ngày và giờ nếu:
    // 1. Đây là tin nhắn đầu tiên của cuộc trò chuyện (lastMessageTime === null)
    // 2. Tin nhắn được gửi sang một ngày khác (không cùng ngày với tin nhắn trước đó)
    // 3. Tin nhắn được gửi cách tin nhắn trước đó hơn 5 phút (300 giây)
    if (lastMessageTime === null || (timeDifference > 300) || (lastMessageTime.getDate() !== currentMessageTime.getDate())) {
        const formattedDate = currentMessageTime.toLocaleDateString('vi-VN', {
            weekday: 'long',
            day: 'numeric',
            month: 'long'
        });
        messageHtml += `<div class="text-center text-gray-400 text-xs my-2">${formattedDate} ${formattedTime}</div>`;
    }

    // Tạo phần tử tin nhắn
    const msgClass = isSelf 
        ? 'bg-blue-500 text-white rounded-bl-xl rounded-tr-xl' 
        : 'bg-gray-200 text-gray-800 rounded-br-xl rounded-tl-xl';
    
    messageHtml += `
        <div class="flex ${align}">
            <p class="inline-block px-4 py-2 ${msgClass} shadow-md max-w-[75%] break-words">
                ${message}
            </p>
        </div>
    `;

    $("#userChatBox").append(messageHtml);
    
    // Cập nhật thời gian của tin nhắn cuối cùng đã được hiển thị
    lastMessageTime = currentMessageTime;

    scrollToBottom();
}
    function loadConversation() {
    $.get("{{ route('chat.last') }}", function(res) {
        if (!res.conversation || res.messages.length === 0) {
            $("#userChatBox").html(`
                <div class="text-center text-gray-400 italic placeholder-message">
                    Chào bạn! Nhấn vào đây để bắt đầu trò chuyện với Nexus Admin.
                </div>
            `);
            return;
        }

        conversationId = res.conversation.id;

        if (channel) pusher.unsubscribe(channel.name);
        channel = pusher.subscribe('chat.' + conversationId);
        channel.bind('chat', function(data) {
            appendMessage(data.message.message, data.message.sender, false, data.message.client_id, data.message.created_at);
        });

        // Đặt lại lastMessageTime trước khi load lịch sử
        lastMessageTime = null;
        res.messages.forEach(msg => appendMessage(msg.message, msg.sender, false, msg.client_id, msg.created_at));
    });
}

    loadConversation();

    function sendMessage() {
    const message = input.val().trim();
    if (!message) return;

    const clientId = Date.now().toString();
    const timestamp = new Date().toISOString(); // Lấy timestamp hiện tại
    appendMessage(message, 'self', false, clientId, timestamp);
    input.val('');

    $.ajax({
        url: "{{ route('send.message') }}",
        method: "POST",
        data: {
            _token: csrfToken,
            message: message,
            client_id: clientId
        },
        success: function(res) {
                // Sửa lỗi ở đây
                if (res.success && !conversationId) {
                    conversationId = res.message.conversation_id;
                    if (channel) pusher.unsubscribe(channel.name);
                    channel = pusher.subscribe('chat.' + conversationId);
                    channel.bind('chat', function(data) {
                        appendMessage(data.message.message, data.message.sender, false, data.message.client_id, data.message.created_at);
                    });
                }
        },
        error: function() {
            appendMessage("Không gửi được tin nhắn!", 'self', true, clientId);
        }
    });
}

    input.on("keypress", e => { if(e.key === "Enter") sendMessage(); });
    sendBtn.on("click", sendMessage);

    // ESC để đóng chat
    $(document).on("keydown", e => {
        if(e.key === "Escape" && !chatPopup.hasClass("hidden")) {
            chatPopup.addClass("hidden");
            chatBubble.removeClass("hidden");
        }
    });
});
</script>
