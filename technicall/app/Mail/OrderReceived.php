<?php

namespace App\Mail;

use App\Models\Order; // <-- Import Order
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReceived extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Order
     */
    public $order; // <-- Make it public

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order) // <-- Pass the Order in
    {
        // Eager load all the data we need for the email
        $this->order = $order->load('student', 'orderItems.product', 'orderItems.photo');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // We get the "from" address from .env
            // Set the subject line here
            subject: 'New Photo Order Received: '.$this->order->student->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // Tell it to use our new email view
            view: 'emails.order-received',
        );
    }

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
