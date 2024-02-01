<?php 

namespace App\Services\Achievements;
use App\Enums\AchievementsEnum;
use App\Enums\CommentsAchievementEnum;
use App\Models\Achievement;
use App\Models\User;

class AchievementsService {

    protected $achievements;
    protected $commentAchievements;
    
    public function __construct()
    {
        $this->achievements = [
            1 => AchievementsEnum::FirtLesson->value,
            5 => AchievementsEnum::FiveLessons->value,
            10 => AchievementsEnum::TenLessons->value,
            25 => AchievementsEnum::TwentyFiveLessons->value,
            50 => AchievementsEnum::FifthLessons->value,
        ];
    
        $this->commentAchievements = [
            1 => CommentsAchievementEnum::FirtComment->value,
            3 => CommentsAchievementEnum::ThreeComments->value,
            5 => CommentsAchievementEnum::FiveComments->value,
            10 => CommentsAchievementEnum::TenComments->value,
            20 => CommentsAchievementEnum::TwentyComments->value
        ];
    }

    public function checkNewLessonAchievement($user): array 
    {
        $userLessonsCount = $user->watched->count();

        if($userLessonsCount === 0){
            return [];
        }

        foreach ($this->achievements as $numberOfLessons => $achievementName) {
            if($userLessonsCount === $numberOfLessons && !Achievement::where('user_id', $user->id)->where('name', $achievementName)->exists())
            {
                return ['name' => $achievementName];
            }
        }

        return [];
    }

    public function checkNewCommentAchievement($user): array 
    {
        $userCommentsCount = $user->comments->count();

        if($userCommentsCount === 0){
            return [];
        }

        foreach ($this->commentAchievements as $numberOfComments => $achievementName) {
            if($userCommentsCount === $numberOfComments && !Achievement::where('user_id', $user->id)->where('name', $achievementName)->exists())
            {
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