<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BirthDayWish extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->subject('Happy Birthday ')
        //     ->view('emails.birthdayWish');

        return $this->subject('Mail from Laravel Job Scheduler')
            ->view('emails.birthdayWish')
            ->from('dev2.apolloinfotech@gmail.com');
    }
}
