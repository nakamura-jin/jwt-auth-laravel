<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminSendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendData)
    {
        $this->sendData = $sendData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        $this->from(config('mail.from'))
            ->subject($this->sendData['title'])
            ->view('admin_send')
            ->with(['text' => $this->sendData['text'], 'name' => $this->sendData['name']]);
    }
}
