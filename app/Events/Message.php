<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Message implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $type;
    public $content;
    public $time;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $content, $time)
    {
        $this->type = $type;
        $this->content = $content;
        $this->time = $time;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        \Log::log('debug', 'Message::broadcastOn - chat-channel - '.json_encode($this));

        return new PrivateChannel('chat-channel');
    }
}
