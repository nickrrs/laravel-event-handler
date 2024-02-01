<?php 

namespace App\Services\Badges;

use App\Enums\BadgesEnum;
use App\Models\User;

class BadgesService {

    protected $badges;
    public function __construct()
    {
        $this->badges = [
            0 => BadgesEnum::Beginner->value,
            4 => BadgesEnum::Intermediate->value,
            8 => BadgesEnum::Advanced->value,
            10 => BadgesEnum::Master->value,
        ];
    }

    public function checkNewBadge(User $user)
    {
        $totalAchievements = $user->achievements()->count();
        $currentBadge = $user->badges()->latest('id')->first();
        $newBadgeName = $this->getBadgeNameBasedOnAchievements($totalAchievements);

        if (!$currentBadge || $currentBadge->name !== $newBadgeName) {
            return ['name' => $newBadgeName];
        }
    }

    private function getBadgeNameBasedOnAchievements($totalAchievements)
    {
        if ($totalAchievements >= 10) {
            return 'Master';
        }
        
        if ($totalAchievements >= 8) {
            return 'Advanced';
        }

        if ($totalAchievements >= 4) {
            return 'Intermediate';
        } 

        return 'Beginner';
        
    }

    public function getCurrentBadge(User $user): string 
    {
        $achievementCount = $user->achievements()->count();
        foreach ($this->badges as $number => $name) {
            if ($achievementCount >= $number) {
                $currentBadge = $name;
            }
        }

        return $currentBadge ?? 'Beginner';
    }

    public function getNextBadge(User $user): string 
    {
        $achievementCount = $user->achievements()->count();
        foreach ($this->badges as $number => $name) {
            if ($achievementCount < $number) {
                return $name;
            }
        }

        return 'Master';
    }

    public function getRemainingToUnlockNextBadge(User $user, string $nextBadge): int 
    {
        $achievementCount = $user->achievements()->count();
        $achievementsNeededForNextBadge = array_search($nextBadge, $this->badges);

        return max($achievementsNeededForNextBadge - $achievementCount, 0);
    }
}