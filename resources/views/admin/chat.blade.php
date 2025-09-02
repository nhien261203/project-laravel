@extends('layout.admin')
@section('content')
<div class="h-[78vh] flex bg-gray-100 antialiased font-sans">

    <!-- Sidebar -->
    <div class="w-1/4 border-r bg-white flex flex-col shadow-sm">
        <div class="p-4 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Conversations</h2>
        </div>
        <ul id="conversationList" class="flex-1 overflow-y-auto">
            <!-- Conversations will be loaded here -->
        </ul>
    </div>

    <!-- Chat Main -->
    <div class="flex-1 flex flex-col bg-gray-50">
        <div id="chatHeader" class="bg-white p-4 border-b shadow-md flex items-center justify-between hidden">
            <h2 id="chatUserName" class="text-xl font-bold text-gray-800"></h2>
            <!-- Nút đóng hoặc thông tin thêm có thể thêm tại đây -->
        </div>
        <div id="chatBox" class="flex-1 overflow-y-auto p-4 text-gray-700">
            <p class="text-center text-gray-400 mt-10 text-lg">Chọn một cuộc trò chuyện để bắt đầu</p>
        </div>
        <form id="replyForm" class="p-4 border-t bg-white shadow-lg hidden">
            <div class="flex items-center">
                <input type="text" id="replyMessage" placeholder="Nhập tin nhắn..."
                       class="flex-1 border-2 border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:border-blue-500 transition-all duration-300">
                <button type="submit" class="ml-2 px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all duration-300">
                    Gửi
                </button>
            </div>
        </form>
    </div>
</div> 
@endsection
    @push('scripts')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;
        const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });

        let currentConversationId = null;
        let channel = null;
        const renderedMessages = new Set();
        const renderedClientIds = new Set();
        const lastMessageIds = {};
        
        // =====================
        // Load conversation list
        // =====================
        function loadConversations() {
            fetch('/admin/conversations')
            .then(res => res.json())
            .then(conversations => {
                const list = document.getElementById('conversationList');
                list.innerHTML = '';
                conversations.forEach(conv => {
                    const li = document.createElement('li');
                    li.className = "p-4 border-b hover:bg-gray-100 cursor-pointer flex justify-between items-center transition duration-200";
                    li.setAttribute('data-id', conv.id);

                    const nameSpan = document.createElement('span');
                    nameSpan.innerText = conv.user ? conv.user.name : 'Guest';
                    nameSpan.className = "font-medium text-gray-700";

                    li.appendChild(nameSpan);

                    li.onclick = () => {
                        loadConversation(conv.id, conv.user ? conv.user.name : 'Guest');
                    };
                    list.appendChild(li);
                });
            });
        }

        // =====================
        // Load conversation messages
        // =====================
        function loadConversation(conversationId, userName){
            // Cập nhật trạng thái active trên sidebar
            const listItems = document.querySelectorAll('#conversationList li');
            listItems.forEach(item => {
                item.classList.remove('bg-blue-100', 'border-l-4', 'border-blue-500');
            });
            const activeItem = document.querySelector(`li[data-id='${conversationId}']`);
            if(activeItem) {
                activeItem.classList.add('bg-blue-100', 'border-l-4', 'border-blue-500');
            }
            
            // Set header chat
            document.getElementById('chatHeader').classList.remove('hidden');
            document.getElementById('chatUserName').innerText = userName;

            // Hủy đăng ký kênh Pusher cũ trước khi đăng ký kênh mới
            if(channel && currentConversationId !== conversationId){
                channel.unbind_all();
                pusher.unsubscribe(channel.name);
                renderedMessages.clear();
                renderedClientIds.clear();
            }

            currentConversationId = conversationId;

            // Đặt lại lastMessageTime trước khi load lịch sử của cuộc trò chuyện mới
            lastMessageTime = null;

            // Đăng ký kênh mới
            channel = pusher.subscribe('chat.' + conversationId);
            channel.bind('chat', function(data){ 
                const msg = data.message;
                if (!renderedMessages.has(msg.id) && !renderedClientIds.has(msg.client_id)) {
                    renderMessage(msg);
                }
            });

            const chatBox = document.getElementById('chatBox');
            chatBox.innerHTML = '';
            
            const afterId = lastMessageIds[conversationId] || 0;
            fetch(`/messages/${conversationId}?after_id=${afterId}`)
            .then(res => res.json())
            .then(messages => {
                // Render tin nhắn theo thứ tự
                messages.forEach(msg => {
                    renderMessage(msg);
                    if(msg.id) renderedMessages.add(msg.id);
                    if(msg.client_id) renderedClientIds.add(msg.client_id);
                });
                document.getElementById('replyForm').classList.remove('hidden');
                chatBox.scrollTop = chatBox.scrollHeight;
            });
        }

        // Render message
        // =====================
        function renderMessage(msg){
            if(!msg) return;

            if(msg.client_id && renderedClientIds.has(msg.client_id)) return;
            if(msg.client_id) renderedClientIds.add(msg.client_id);

            if(msg.id && renderedMessages.has(msg.id)) return;
            if(msg.id) renderedMessages.add(msg.id);

            const box = document.getElementById('chatBox');
            
            // Lấy thời gian của tin nhắn
            const currentMessageTime = new Date(msg.created_at || Date.now());

            // Định dạng thời gian và ngày
            const formattedTime = currentMessageTime.toLocaleTimeString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Logic hiển thị ngày và giờ
            const timeDifference = lastMessageTime ? (currentMessageTime - lastMessageTime) / 1000 : Infinity;
            const isDifferentDay = lastMessageTime ? currentMessageTime.toDateString() !== lastMessageTime.toDateString() : true;

            if (isDifferentDay || timeDifference > 300) { // 300 giây = 5 phút
                let dateDisplay = '';
                const today = new Date();
                if (currentMessageTime.toDateString() === today.toDateString()) {
                    dateDisplay = 'Hôm nay';
                } else {
                    dateDisplay = currentMessageTime.toLocaleDateString('vi-VN', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long'
                    });
                }
                
                const timeDiv = document.createElement('div');
                timeDiv.className = "text-center text-gray-400 text-xs my-2";
                timeDiv.innerText = `${dateDisplay} ${formattedTime}`;
                box.appendChild(timeDiv);
            }

            const div = document.createElement('div');
            const sender = msg.sender === 'user' ? 'user' : 'admin';
            div.className = "mb-2 flex " + (sender ==='user' ? 'justify-start' : 'justify-end');
            
            div.innerHTML = `<div class="px-4 py-2 rounded-xl text-sm max-w-[70%] break-words shadow-md ${sender === 'user' ? 'bg-white text-gray-800' : 'bg-blue-500 text-white'}">
                                ${msg.message}
                            </div>`;
            box.appendChild(div);
            box.scrollTop = box.scrollHeight;

            // Cập nhật thời gian của tin nhắn cuối cùng
            lastMessageTime = currentMessageTime;
        }
        
        // =====================
        // Send reply (Optimistic UI & Fix DB save)
        // =====================
        document.getElementById('replyForm').addEventListener('submit', function(e){
            e.preventDefault();
            const messageInput = document.getElementById('replyMessage');
            const message = messageInput.value.trim();
            if(!message || !currentConversationId) return;

            // Thêm client_id để backend nhận và lưu vào DB
            const clientId = Date.now().toString();

            // Hiển thị tin nhắn ngay lập tức (Optimistic UI)
            renderMessage({message, sender:'admin', client_id:clientId, conversation_id:currentConversationId});
            renderedClientIds.add(clientId); // Lưu client_id của tin nhắn vừa gửi
            messageInput.value = '';

            // Gửi tin nhắn lên server
            fetch('/admin/send-reply', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ 
                    conversation_id: currentConversationId, 
                    message: message,
                    client_id: clientId // <-- TRƯỜNG NÀY ĐÃ ĐƯỢC THÊM LẠI
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gửi tin nhắn thất bại.');
                }
                return response.json();
            })
            .then(data => {
                // Sau khi tin nhắn được lưu vào DB, đánh dấu ID đã được render
                if (data.success && data.message.id) {
                    renderedMessages.add(data.message.id);
                    // Có thể xóa client_id cũ nếu muốn
                    renderedClientIds.delete(data.message.client_id);
                }
            })
            .catch(error => {
                console.error("Lỗi gửi tin nhắn:", error);
                alert("Lỗi: " + error.message);
            });
        });

        loadConversations();
        
        // --- THÊM PHẦN NÀY ĐỂ LẮNG NGHE SỰ KIỆN TỪ PUSHER ---
        // Lắng nghe kênh chung cho admin để cập nhật danh sách cuộc trò chuyện
        const adminChannel = pusher.subscribe('admin-channel');
        adminChannel.bind('new.conversation.created', function(data) {
            // Khi nhận được sự kiện, tải lại danh sách cuộc trò chuyện
            console.log('Có một cuộc trò chuyện mới được tạo:', data);
            loadConversations();
        });
    </script>
    @endpush
