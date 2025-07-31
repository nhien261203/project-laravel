<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\ContactUserConfirmMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserContactController extends Controller
{
    public function show()
    {
        return view('user.contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\pL\s\-]+$/u' // chỉ chữ cái, khoảng trắng, gạch ngang
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:150'
            ],
            'phone' => [
                'nullable',
                'string',
                'min:8',
                'max:20',
                'regex:/^(\+84|0)[0-9]{8,}$/'
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ]
        ]);

        $contact = Contact::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);

        // Gửi mail xác nhận cho người dùng
        Mail::to($contact->email)->send(new ContactUserConfirmMail($contact));

        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm.');
    }
}
