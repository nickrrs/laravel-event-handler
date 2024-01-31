<?php 

namespace App\Services\Badges;

use App\Models\User;
use App\Repository\Users\UsersRepository;

class BadgesService {

    private $usersRepository;
    public function __construct(UsersRepository $usersRepository){
        $this->usersRepository = $usersRepository;
    }

    public function checkNewBadge(User $user){
        $totalAchievements = $user->achievements()->count();
        $currentBadge = $user->badges()->latest('id')->first();
        $newBadgeName = $this->getBadgeNameBasedOnAchievements($totalAchievements);

        if (!$currentBadge || $currentBadge->name !== $newBadgeName) {
            return ['name' => $newBadgeName];
        }
    }

    private function getBadgeNameBasedOnAchievements($totalAchievements){
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
}