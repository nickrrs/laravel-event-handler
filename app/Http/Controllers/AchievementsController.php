<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Achievements\AchievementsService;
use App\Services\Badges\BadgesService;

class AchievementsController extends Controller {
    
    private $achievementService;
    private $badgeService;

    public function __construct(AchievementsService $achievementService, BadgesService $badgeService)
    {
        $this->achievementService = $achievementService;
        $this->badgeService = $badgeService;
    }

    public function index(User $user)
    {
        $unlockedAchievements = $this->achievementService->getUnlockedAchievements($user);
        $nextAvailableAchievements = $this->achievementService->getNextAvailableAchievements($user);
        $currentBadge = $this->badgeService->getCurrentBadge($user);
        $nextBadge = $this->badgeService->getNextBadge($user);
        $remainingToUnlockNextBadge = $this->badgeService->getRemainingToUnlockNextBadge($user, $nextBadge);
        
        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            'remaing_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    }
}
