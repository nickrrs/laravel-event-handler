<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BadgeUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $badge;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, String $badge)
    {
        $this->user = $user;
        $this->badge = $badge;
        
    }
}
