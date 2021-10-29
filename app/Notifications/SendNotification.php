<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;

class SendNotification extends Notification
{
    use Queueable;
    public $notifdata;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notifdata)
    {
        $this->notifdata = $notifdata;
        $messaging = app('firebase.messaging');
        $message = CloudMessage::fromArray($this->notifdata);
        $messaging->send($message);
        return true;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //
    }
}
