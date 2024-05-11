<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;
class NotifySeminar extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    
    public function __construct(array $data)
    {
        $this->data=$data;
    }

  
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->data['subject'],
        );
    }



    public function content(): Content
    {
        return new Content(
            view: 'emails.NotifySeminar',
        );
    }


    public function attachments(): array
    {
        return [];
    }

}
   
