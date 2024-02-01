<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Models\User;
use App\Services\Achievements\AchievementsService;
use App\Services\Badges\BadgesService;
use App\Services\Users\UsersService;

class CommentWrittenListener
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
    public function handle(CommentWritten $event): void
    {
        
        $user = User::find($event->user_id);
        $this->handleAchievements($user);
        $this->handleBadges($user);
    }

    private function handleAchievements(User $user){
        $achievement = $this->achievementService->checkNewCommentAchievement($user);
     
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

