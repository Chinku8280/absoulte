<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $subject;
    public $body;
    public $company_name;
    public $lead_id;
    public $attachment;
    public $paymentLink;
    public $data;
    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        //
       
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        Log::info('Email Data:', $this->data);
        return $this->view('admin.leads.payment-mail')
                     ->with(['data' => $this->data])
                    ->attachData(base64_decode($this->attachment), 'document.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            // subject: 'Invoice Mail',
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
