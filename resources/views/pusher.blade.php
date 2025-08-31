<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chat Laravel Pusher | Edlin App</title>
  <link rel="icon" href="https://assets.edlin.app/favicon/favicon.ico"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css'])

  <!-- JS -->
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body class="bg-gray-100">

<div class="chat max-w-lg mx-auto mt-10 bg-white rounded-lg shadow-md flex flex-col h-[600px]">

  <!-- Header -->
  <div class="top flex items-center border-b border-gray-200 px-6 py-4">
    <div class="ml-4 overflow-hidden">
      <p class="text-gray-800 font-semibold text-base truncate">Ross Edlin</p>
      <small class="text-sm text-gray-500">Online</small>
    </div>
  </div>

  <!-- Messages -->
  <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-2">
    <!-- Tin nhắn mặc định -->
    <div class="message receive flex items-start mb-4">
      <p class="inline-flex text-sm text-white bg-blue-500 rounded-md px-4 py-2 m-1">
        Hey! What's up! 👋
      </p>
    </div>
    <div class="message receive flex items-start mb-4">
      <p class="inline-flex text-sm text-white bg-blue-500 rounded-md px-4 py-2 m-1">
        Ask a friend to open this link and you can chat with them!
      </p>
    </div>
  </div>

  <!-- Footer -->
  <div class="bottom flex items-center border-t border-gray-200 py-4 px-4">
    <form id="chatForm" class="flex w-full gap-2">
      <input 
        type="text" 
        id="message" 
        name="message" 
        placeholder="Enter message..." 
        autocomplete="off"
        class="flex-1 px-3 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:border-blue-400 focus:ring focus:ring-blue-100 outline-none"
      />
      <button 
        type="submit"
        class="flex items-center justify-center w-10 h-10 border border-gray-700 rounded-md cursor-pointer bg-[url('https://assets.edlin.app/icons/font-awesome/paper-plane/paper-plane-regular.svg')] bg-center bg-no-repeat"
      ></button>
    </form>
  </div>

</div>

<script>
$(document).ready(function() {
  const messages = $("#messages");

  // Hàm render tin nhắn
  function renderMessage(msg, type = 'receive') {
    const html = `
      <div class="message ${type} flex items-start mb-4 ${type === 'broadcast' ? 'justify-end' : ''}">
        <p class="inline-flex text-sm ${type === 'broadcast' ? 'text-gray-800 bg-gray-200' : 'text-white bg-blue-500'} rounded-md px-4 py-2 m-1">
          ${msg}
        </p>
      </div>
    `;
    messages.append(html);
    messages.scrollTop(messages[0].scrollHeight);
  }

  // Pusher
  const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', { cluster: 'ap1' });
  const channel = pusher.subscribe('public');

  channel.bind('pusher:subscription_succeeded', function() {
    console.log("✅ Đã kết nối tới kênh public");
  });

  // Khi nhận tin nhắn
  channel.bind('chat', function(data) {
    console.log("📩 Nhận tin nhắn:", data.message);
    renderMessage(data.message, 'receive');
  });

  // Khi gửi tin nhắn
  $("#chatForm").submit(function(e) {
    e.preventDefault();
    const msg = $("#message").val();
    if(!msg.trim()) return;

    $.ajax({
      url: '/chat/broadcast',
      method: 'POST',
      headers: { 'X-Socket-Id': pusher.connection.socket_id },
      data: {
        _token: '{{ csrf_token() }}',
        message: msg
      }
    }).done(function(res) {
      console.log("✅ Broadcast thành công:", res);
      renderMessage(res.message, 'broadcast');
      $("#message").val('');
    }).fail(function(xhr) {
      console.error("❌ Lỗi khi broadcast:", xhr.responseText);
    });
  });
});
</script>

</body>
</html>
