<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsUser extends Mailable {
    use Queueable, SerializesModels;

    private $name;
    private $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $text) {
        $this->name = $name;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('email.contact_user', [
            "name" => $this->name,
            "text" => $this->text,
        ]);
    }
}
