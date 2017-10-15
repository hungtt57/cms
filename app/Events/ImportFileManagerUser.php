<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ImportFileManagerUser extends Event
{
    use SerializesModels;

    public $email;
    public $filepath;
    public $originalFileName;
    public $icheck_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($email, $filepath, $originalFileName, $icheck_id)
    {
        $this->email = $email;
        $this->filepath = $filepath;
        $this->originalFileName = $originalFileName;
        $this->icheck_id = $icheck_id;
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
