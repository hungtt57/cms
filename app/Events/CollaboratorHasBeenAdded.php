<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Enterprise\Collaborator;

class CollaboratorHasBeenAdded extends Event
{
    use SerializesModels;

    public $collaborator;
    public $password;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Collaborator $collaborator, $password)
    {
        $this->collaborator = $collaborator;
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
