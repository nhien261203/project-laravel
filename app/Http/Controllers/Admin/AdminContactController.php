<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        // Tìm theo từ khóa
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('email', 'like', '%' . $request->keyword . '%')
                    ->orWhere('message', 'like', '%' . $request->keyword . '%');
            });
        }

        // Lọc theo trạng thái đã phản hồi
        if ($request->filled('is_replied')) {
            $query->where('is_replied', $request->is_replied);
        }

        $contacts = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.contacts.index', compact('contacts'));
    }


    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    public function markReplied(Contact $contact)
    {
        $contact->update(['is_replied' => true]);
        return back()->with('success', 'Đã đánh dấu là đã phản hồi.');
    }
    public function markUnreplied(Contact $contact)
    {
        $contact->update(['is_replied' => false]);
        return back()->with('success', 'Đã đặt lại trạng thái chưa phản hồi.');
    }


    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', 'Đã xoá liên hệ.');
    }
}
