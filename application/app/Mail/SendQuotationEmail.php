<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendQuotationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $subject;
    public $body;
    public $company_name;
    public $attachment;
    /**
     * Create a new message instance.
     */

    public function __construct($title,$subject,$body,$company_name,$attachment)
    {
        //
        $this->title = $title;
        $this->subject = $subject;
        $this->body = $body;
        $this->company_name = $company_name;
        $this->attachment = $attachment;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('admin.quotation.mail')
                    ->attachData(base64_decode($this->attachment), 'document.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
