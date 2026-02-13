<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;

class DataChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $entity) {}

    public function broadcastOn()
    {
        return new Channel('data-changed');
    }

    public function broadcastAs()
    {
        return 'changed';
    }

    public function broadcastWith()
    {
        return [
            'entity'  => $this->entity,
            'user_id' => Session::get('karyawan_id'),
        ];
    }
}
