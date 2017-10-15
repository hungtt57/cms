<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductsFileUploaded extends Event
{
    use SerializesModels;

    public $email;
    public $filepath;
    public $originalFileName;
    public $new;
    public $prefix;
    public $vendor;
    public $jobName;
    public $createBy;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($email, $filepath, $originalFileName, $new,$prefix,$vendor)
    {
        $this->email = $email;
        $this->filepath = $filepath;
        $this->originalFileName = $originalFileName;
        $this->new = (bool) $new;
        $this->prefix = $prefix;
        $this->vendor = $vendor;
        $this->createBy =$email;
        $this->jobName ='Import sản phẩm hệ thống';
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
