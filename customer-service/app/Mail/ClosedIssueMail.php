<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClosedIssueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your issue was closed.')
            ->view('emails.closed-issue');
    }
}
