<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsAdmin extends Mailable {
    use Queueable, SerializesModels;

    private $name;
    private $email;
    private $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $text) {
        $this->name = $name;
        $this->email = $email;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('email.contact_admin', [
            "name" => $this->name,
            "email" => $this->email,
            "text" => $this->text,
        ]);
    }
}
