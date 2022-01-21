<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WishlistUpdate {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id;
    public $productId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $productId) {
        $this->id = $id;
        $this->productId = $productId;
    }

    public function broadcastOn() {
        return ['wishlist-item'];
    }

    public function broadcastAs() {
        return "wishlist-item-{$this->id}";
    }
}
