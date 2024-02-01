<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Services\Users\UsersService;

class AchievementUnlockedListener
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
    public function handle(AchievementUnlocked $event): void
    {
        $this->handleNewAchievement($event);
    }

    private function handleNewAchievement($event)
    {
        $this->usersService->storeNewAchievement($event);
    }
}
