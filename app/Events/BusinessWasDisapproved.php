<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Enterprise\Business;

class BusinessWasDisapproved extends Event
{
    use SerializesModels;

    public $business;
    public $reason;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Business $business, $reason)
    {
        $this->business = $business;
        $this->reason = $reason;
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
