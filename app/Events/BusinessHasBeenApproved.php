<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Enterprise\Business;

class BusinessHasBeenApproved extends Event
{
    use SerializesModels;

    public $business;
    public $password;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Business $business, $password)
    {
        $this->business = $business;
        $this->password = encrypt($password);
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
