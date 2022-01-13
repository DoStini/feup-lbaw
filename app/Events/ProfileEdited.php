<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileEdited implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id) {
        $this->id = $id;
        $this->message = "Your profile was edited by an admin";
    }

    public function broadcastOn() {
        return ['profile-edited'];
    }

    public function broadcastAs() {
        return "profile-edited-{$this->id}";
    }
}
