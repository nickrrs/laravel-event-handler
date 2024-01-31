<?php 

namespace App\Services\Achievements;
use App\Enums\AchievementsEnum;
use App\Models\Achievement;

class AchievementsService {

    public function __construct(){
    }

    public function checkNewAchievement($user): array {
        $userLessonsCount = $user->watched->count();

        if($userLessonsCount == 0){
            return [];
        }

        $achievements = [
            1 => AchievementsEnum::FirtLesson->value,
            5 => AchievementsEnum::FiveLessons->value,
            10 => AchievementsEnum::TenLessons->value,
            25 => AchievementsEnum::TwentyFiveLessons->value,
            50 => AchievementsEnum::FifthLessons->value,
        ];

        foreach ($achievements as $numberOfLessons => $achievementName) {
            if($userLessonsCount == $numberOfLessons && !Achievement::where('user_id', $user->id)->where('name', $achievementName)->exists()){
                return ['name' => $achievementName];
            }
        }

        return [];
    }

}