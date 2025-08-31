<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Chat</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@vite(['resources/css/app.css'])
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="h-screen flex bg-gray-100">

<!-- Sidebar -->
<div class="w-1/4 border-r bg-white overflow-y-auto">
    <h2 class="text-lg font-bold p-4 border-b">Conversations</h2>
    <ul id="conversationList"></ul>
</div>

<!-- Chat Main -->
<div class="flex-1 flex flex-col">
    <div id="chatBox" class="flex-1 overflow-y-auto p-4 text-gray-700 bg-gray-50">
        <p class="text-center text-gray-400 mt-10">Chọn một cuộc trò chuyện để bắt đầu</p>
    </div>
    <form id="replyForm" class="flex p-3 border-t bg-white hidden">
        <input type="text" id="replyMessage" placeholder="Nhập tin nhắn..."
               class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
          Gửi
        </button>
    </form>
</div>

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
const unreadCounts = {}; // số tin nhắn chưa đọc

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
            li.className = "p-3 border-b hover:bg-gray-100 cursor-pointer flex justify-between items-center";

            const nameSpan = document.createElement('span');
            nameSpan.innerText = conv.user ? conv.user.name : 'Guest';

            const badge = document.createElement('span');
            badge.className = "bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full hidden";
            badge.id = `badge-${conv.id}`;
            badge.innerText = unreadCounts[conv.id] || '';

            li.appendChild(nameSpan);
            li.appendChild(badge);

            li.onclick = () => {
                loadConversation(conv.id);
                unreadCounts[conv.id] = 0;
                updateBadge(conv.id);
            };
            list.appendChild(li);
        });
    });
}

// =====================
// Update badge
// =====================
function updateBadge(conversationId){
    const badge = document.getElementById(`badge-${conversationId}`);
    if(!badge) return;
    const count = unreadCounts[conversationId] || 0;
    if(count>0){
        badge.innerText = count;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

// =====================
// Load conversation messages
// =====================
function loadConversation(conversationId){
    currentConversationId = conversationId;

    if(channel){
        channel.unbind_all();
        pusher.unsubscribe(channel.name);
    }

    channel = pusher.subscribe('chat.' + conversationId);
    channel.bind('chat', function(data){
        const msg = data.message;
        msg.conversation_id = conversationId;
        renderMessage(msg);
        lastMessageIds[conversationId] = msg.id;
    });

    const afterId = lastMessageIds[conversationId] || 0;
    fetch(`/messages/${conversationId}?after_id=${afterId}`)
    .then(res => res.json())
    .then(messages => {
        const box = document.getElementById('chatBox');
        if(afterId===0) box.innerHTML = '';
        messages.forEach(msg => {
            msg.conversation_id = conversationId;
            renderMessage(msg);
            lastMessageIds[conversationId] = msg.id;
        });
        document.getElementById('replyForm').classList.remove('hidden');
    });
}

// =====================
// Render message
// =====================
function renderMessage(msg){
    if(!msg) return;

    if(msg.client_id && renderedClientIds.has(msg.client_id)) return;
    if(msg.client_id) renderedClientIds.add(msg.client_id);

    if(msg.id && renderedMessages.has(msg.id)) return;
    if(msg.id) renderedMessages.add(msg.id);

    const box = document.getElementById('chatBox');
    const div = document.createElement('div');
    div.className = "mb-2 flex " + (msg.sender==='user' ? 'justify-start' : 'justify-end');
    div.innerHTML = `<div class="px-3 py-2 rounded-lg ${msg.sender==='user'?'bg-gray-200 text-gray-800':'bg-blue-500 text-white'} max-w-[70%] break-words">
                        ${msg.message}
                     </div>`;
    box.appendChild(div);
    box.scrollTop = box.scrollHeight;

    // Tăng badge nếu là tin nhắn user và conversation chưa mở
    if(msg.sender==='user' && currentConversationId !== msg.conversation_id){
        unreadCounts[msg.conversation_id] = (unreadCounts[msg.conversation_id] || 0) + 1;
        updateBadge(msg.conversation_id);
    }
}

// =====================
// Send reply (Optimistic UI)
// =====================
document.getElementById('replyForm').addEventListener('submit', function(e){
    e.preventDefault();
    const message = document.getElementById('replyMessage').value.trim();
    if(!message || !currentConversationId) return;

    const clientId = Date.now().toString();
    renderMessage({message, sender:'admin', client_id:clientId, conversation_id:currentConversationId});
    renderedClientIds.add(clientId);
    document.getElementById('replyMessage').value='';

    fetch('/admin/send-reply',{
        method:'POST',
        headers:{ 
            'Content-Type':'application/json', 
            'X-CSRF-TOKEN':'{{ csrf_token() }}' 
        },
        body: JSON.stringify({ 
            conversation_id: currentConversationId, 
            message: message, 
            client_id: clientId 
        })
    }).then(res=>res.json())
      .then(data=>{
        if(data.success){
            lastMessageIds[currentConversationId] = data.message.id;
        } else {
            console.error("Lỗi gửi:", data);
        }
    });
});

loadConversations();
</script>
</body>
</html>
