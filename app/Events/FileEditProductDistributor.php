<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FileEditProductDistributor extends Event
{
    use SerializesModels;

    public $businessId;
    public $filepath;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($businessId, $filepath)
    {
        $this->businessId = $businessId;
        $this->filepath = $filepath;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
