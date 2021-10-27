<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $maildata;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        if ($maildata['markdown'] == 'mails.assessment-created') {
            $maildata['subject'] = 'Assessment Created Notification';
        } elseif ($maildata['markdown'] == 'mails.booking-created') {
            $maildata['subject'] = 'Booking Created Notification';
        } elseif ($maildata['markdown'] == 'mails.booking-checkin') {
            $maildata['subject'] = 'Booking Check In Notification';
        } elseif ($maildata['markdown'] == 'mails.booking-checkout') {
            $maildata['subject'] = 'Booking Check Out Notification';
        } elseif ($maildata['markdown'] == 'mails.booking-cancel') {
            $maildata['subject'] = 'Booking Cancel Notification';
        }

        $this->maildata = $maildata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->maildata['subject'])
            ->markdown($this->maildata['markdown'])
            ->with('maildata', $this->maildata);
    }
}
