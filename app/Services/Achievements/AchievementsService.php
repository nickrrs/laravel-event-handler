<?php 

namespace App\Services\Achievements;
use App\Enums\AchievementsEnum;
use App\Enums\CommentsEnum;
use App\Models\Achievement;
use App\Models\User;

class AchievementsService {

    protected $achievements;
    protected $commentAchievements;
    
    public function __construct(){
        $this->achievements = [
            1 => AchievementsEnum::FirtLesson->value,
            5 => AchievementsEnum::FiveLessons->value,
            10 => AchievementsEnum::TenLessons->value,
            25 => AchievementsEnum::TwentyFiveLessons->value,
            50 => AchievementsEnum::FifthLessons->value,
        ];
    
        $this->commentAchievements = [
            1 => CommentsEnum::FirtComment->value,
            3 => CommentsEnum::ThreeComments->value,
            5 => CommentsEnum::FiveComments->value,
            10 => CommentsEnum::TenComments->value,
            20 => CommentsEnum::TwentyComments->value
        ];
    }

    public function checkNewAchievement($user): array {
        $userLessonsCount = $user->watched->count();

        if($userLessonsCount == 0){
            return [];
        }

        foreach ($this->achievements as $numberOfLessons => $achievementName) {
            if($userLessonsCount == $numberOfLessons && !Achievement::where('user_id', $user->id)->where('name', $achievementName)->exists()){
                return ['name' => $achievementName];
            }
        }

        return [];
    }

    public function getUnlockedAchievements(User $user): array
    {
        return $user->achievements->pluck('name')->toArray();
    }

    public function getNextAvailableAchievements(User $user): array
    {
        $nextAchievements = [];

        $watchedCount = $user->watched->count();
        $nextAchievements[] = $this->getNextAchievement($this->achievements, $watchedCount);

        $commentsCount = $user->comments->count();
        $nextAchievements[] = $this->getNextAchievement($this->commentAchievements, $commentsCount);

        return array_filter($nextAchievements); // remove null values
    }

    private function getNextAchievement(array $achievements, int $count): ?string
    {
        foreach ($achievements as $number => $name) {
            if ($count < $number) {
                return $name;
            }
        }

        return null;
    }
}