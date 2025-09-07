<?php

namespace App\Jobs;

use App\Mail\ContactUserConfirmMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    public function handle()
    {
        Mail::to($this->contact->email)
            ->send(new ContactUserConfirmMail($this->contact));
    }
}
