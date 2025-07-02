<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Tạo một instance mới.
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Tiêu đề email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Khôi phục mật khẩu'
        );
    }

    /**
     * Truyền dữ liệu vào view.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'token' => $this->token,
                'email' => $this->email,
            ],
        );
    }

    /**
     * Đính kèm nếu cần (ở đây không có).
     */
    public function attachments(): array
    {
        return [];
    }
}
