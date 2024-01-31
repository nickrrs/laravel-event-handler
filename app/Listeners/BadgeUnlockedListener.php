<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Services\Users\UsersService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BadgeUnlockedListener
{
    private $usersService;

    /**
     * Create the event listener.
     */
    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlocked $event): void
    {
        $this->handleNewBadge($event);
    }

    private function handleNewBadge($event){
        $this->usersService->storeNewBadge($event);
    }
}
