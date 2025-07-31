<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xác nhận liên hệ</title>
</head>
<body>
    <p>Xin chào {{ $contact->name }},</p>

    <p>Chúng tôi đã nhận được liên hệ của bạn với nội dung:</p>

    <blockquote>{{ $contact->message }}</blockquote>

    <p>Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>

    <p>— Đội ngũ hỗ trợ NexusPhone</p>
</body>
</html>
