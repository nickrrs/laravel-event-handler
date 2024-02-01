<?php 

namespace App\Services\Badges;

use App\Enums\BadgesEnum;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

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
        try{
            $totalAchievements = $user->achievements()->count();
            $currentBadge = $user->badges()->latest('id')->first();
            $newBadgeName = $this->getBadgeNameBasedOnAchievements($totalAchievements);

            if (!$currentBadge || $currentBadge->name !== $newBadgeName) {
                return ['name' => $newBadgeName];
            }
        } catch (Exception $e){
            Log::alert("Error while trying to check if the user {$user->id} have a new badge to earn: {$e->getMessage()}");
            return $e->getMessage();
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
        try{
            $achievementCount = $user->achievements()->count();
            foreach ($this->badges as $number => $name) {
                if ($achievementCount >= $number) {
                    $currentBadge = $name;
                }
            }

            return $currentBadge ?? 'Beginner';
        } catch (Exception $e){
            Log::alert("Error while trying to check the current badge of the user {$user->id}: {$e->getMessage()}");
            return $e->getMessage();
        }
    }

    public function getNextBadge(User $user): string 
    {
        try{
            $achievementCount = $user->achievements()->count();
            foreach ($this->badges as $number => $name) {
                if ($achievementCount < $number) {
                    return $name;
                }
            }

            return 'Master';
        } catch (Exception $e){
            Log::alert("Error while trying to check the next badge for the user {$user->id}: {$e->getMessage()}");
            return $e->getMessage();
        }
    }

    public function getRemainingToUnlockNextBadge(User $user, string $nextBadge): int 
    {
        try{
            $achievementCount = $user->achievements()->count();
            $achievementsNeededForNextBadge = array_search($nextBadge, $this->badges);

            return max($achievementsNeededForNextBadge - $achievementCount, 0);
        } catch (Exception $e){
            Log::alert("Error while trying to check the remaining achievement quantity to a next badge for the user {$user->id}: {$e->getMessage()}");
            return 0;
        }
    }
}