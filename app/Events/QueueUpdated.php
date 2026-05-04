<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $jadwalId;
    public int $noDilayani;

    /**
     * Create a new event instance.
     */
    public function __construct(int $jadwalId, int $noDilayani)
    {
        $this->jadwalId    = $jadwalId;
        $this->noDilayani  = $noDilayani;
    }

    /**
     * Get the channels the event should broadcast on.
     * Channel per jadwal: queue.{jadwal_id}
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("queue.{$this->jadwalId}"),
        ];
    }

    /**
     * Data yang dikirim ke frontend.
     */
    public function broadcastWith(): array
    {
        return [
            'jadwal_id'   => $this->jadwalId,
            'no_dilayani' => $this->noDilayani,
        ];
    }

    /**
     * Event name yang didengar oleh Laravel Echo.
     */
    public function broadcastAs(): string
    {
        return 'QueueUpdated';
    }
}
