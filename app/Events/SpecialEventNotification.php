<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel; 
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SpecialEventNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;
    /**
     *
     * @param int $userId
     */
    public function __construct($userId,$message)
    {
        $this->message = $message;
        $this->userId = $userId;
    }
    /**
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }
    /**
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'new-event';
    }
}
