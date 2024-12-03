<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LoketUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $lokets;

    public function __construct($lokets)
    {
        $this->lokets = $lokets->toArray(); // Ubah collection menjadi array
    }

    // Tentukan channel yang akan digunakan untuk broadcasting
    public function broadcastOn()
    {
        return new Channel('loket-channel'); // Channel yang digunakan untuk broadcasting
    }
}
