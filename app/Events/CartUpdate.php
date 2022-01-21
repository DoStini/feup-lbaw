<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartUpdate implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id;
    public $product_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $product_id) {
        $this->id = $id;
        $this->product_id = $product_id;
    }

    public function broadcastOn() {
        return ['cart-item'];
    }

    public function broadcastAs() {
        return "cart-item-{$this->id}";
    }
}
