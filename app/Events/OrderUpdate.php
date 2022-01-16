<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdate implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id;
    public $order_id;
    public $order_notif_type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $order_id, $order_notif_type) {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->order_notif_type = $order_notif_type;
    }

    public function broadcastOn() {
        return ['order-status'];
    }

    public function broadcastAs() {
        return "order-status-{$this->id}";
    }
}
