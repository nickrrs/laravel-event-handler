<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\User;
use App\Services\Achievements\AchievementsService;
use App\Services\Badges\BadgesService;
use App\Services\Users\UsersService;

class LessonWatchedListener
{
    private $achievementService;
    private $usersService;
    private $badgesService;

    /**
     * Create the event listener.
     */
    public function __construct(AchievementsService $achievementsService, BadgesService $badgesService, UsersService $usersService)
    {
        $this->achievementService = $achievementsService;
        $this->usersService = $usersService;
        $this->badgesService = $badgesService;
    }

    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event): void
    {
        $user = $event->user;
        $this->handleLessonWatched($event);
        $this->handleAchievements($user);
        $this->handleBadges($user);
    }

    private function handleLessonWatched($event){
        $this->usersService->storeUserLesson($event);
    }
    private function handleAchievements(User $user){
       $achievement = $this->achievementService->checkNewAchievement($user);
 
        if(count($achievement) > 0 ){
          AchievementUnlocked::dispatch($user, $achievement['name']);
        }

    }

    private function handleBadges(User $user){
        $badge = $this->badgesService->checkNewBadge($user);
       
        if(count($badge) > 0 ){
            BadgeUnlocked::dispatch($user, $badge['name']);
        }
    }

}
