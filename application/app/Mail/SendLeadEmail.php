<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendLeadEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $subject;
    public $body;
    public $company_name;
    public $lead_id;
    public $attachment;
    public $paymentLink;
    /**
     * Create a new message instance.
     */
    public function __construct($title,$subject,$body,$company_name,$lead_id,$attachment)
    {
        //
        $this->title = $title;
        $this->subject = $subject;
        $this->body = $body;
        $this->company_name = $company_name;
        $this->lead_id = $lead_id;
        $this->attachment = $attachment;
       // $this->paymentLink = $paymentLink;
    }

    /**
     * Get the message envelope.
     */

     public function build()
     {
         return $this->view('admin.leads.mail')
             ->attachData($this->attachment, [
                'as' => 'document.pdf',
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
    //         view: 'admin.leads.mail',
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
