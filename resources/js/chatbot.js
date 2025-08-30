// nạp dữ liệu sản phẩm từ file products.js
// (trong public/data/products.js có biến window.PRODUCT_DATA)

async function sendMessage() {
    const input = document.getElementById("chatInput");
    const chatBox = document.getElementById("chatBox");
    const userMessage = input.value.trim();

    if (!userMessage) return;

    // hiển thị tin nhắn người dùng
    chatBox.innerHTML += `<div style="text-align:right; margin:5px;">
        <span style="background:#DCF8C6; padding:8px 12px; border-radius:15px; display:inline-block;">
            ${userMessage}
        </span>
    </div>`;

    input.value = "";

    // gọi API Laravel
    const res = await fetch("/chatbot/ask", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            message: userMessage,
            productData: window.PRODUCT_DATA // truyền text sản phẩm vào
        })
    });

    const data = await res.json();
    const answer = data.candidates?.[0]?.content?.parts?.[0]?.text || "Xin lỗi, tôi chưa hiểu.";

    // hiển thị trả lời của bot
    chatBox.innerHTML += `<div style="text-align:left; margin:5px;">
        <span style="background:#F1F0F0; padding:8px 12px; border-radius:15px; display:inline-block;">
            ${answer}
        </span>
    </div>`;

    // cuộn xuống cuối
    chatBox.scrollTop = chatBox.scrollHeight;
}
