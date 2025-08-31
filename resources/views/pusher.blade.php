{{-- resources/views/components/user-chat.blade.php --}}

{{-- Floating chat bubble --}}
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

    function appendMessage(message, sender = 'server', isError = false, clientId = null) {
    // Xóa placeholder nếu còn
        const placeholder = $("#userChatBox .placeholder-message");
        if (placeholder.length) placeholder.remove();

        if (clientId && renderedClientIds.has(clientId)) return;
        if (clientId) renderedClientIds.add(clientId);

        const isSelf = sender === 'user' || sender === 'self';
        let msgClass = isSelf 
            ? 'bg-blue-500 text-white rounded-bl-xl rounded-tr-xl' 
            : 'bg-gray-200 text-gray-800 rounded-br-xl rounded-tl-xl';
        if (isError) msgClass = 'bg-red-500 text-white rounded-bl-xl rounded-tr-xl';

        const align = isSelf ? 'justify-end' : 'justify-start';

        $("#userChatBox").append(`
            <div class="flex ${align}">
                <p class="inline-block px-4 py-2 ${msgClass} shadow-md max-w-[75%] break-words">
                    ${message}
                </p>
            </div>
        `);

        const box = document.getElementById('userChatBox');
        box.scrollTop = box.scrollHeight;
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
                appendMessage(data.message.message, data.message.sender, false, data.message.client_id);
            });

            res.messages.forEach(msg => appendMessage(msg.message, msg.sender, false, msg.client_id));
        });
    }

    loadConversation();

    function sendMessage() {
        const message = input.val().trim();
        if (!message) return;

        const clientId = Date.now().toString();
        appendMessage(message, 'self', false, clientId);
        input.val('');

        $.ajax({
            url: "{{ route('send.message') }}",
            method: "POST",
            data: {
                _token: csrfToken,
                message: message,
                client_id: clientId
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
