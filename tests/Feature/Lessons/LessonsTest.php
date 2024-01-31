<?php 

namespace Tests\Feature\Lessons;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lesson;
use App\Events\LessonWatched;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class LessonsTest extends TestCase
{
    use RefreshDatabase;

    public function testFirstLessonWatchedAchievement() {
        Event::fakeExcept([
            LessonWatched::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        event(new LessonWatched($lesson, $user));

        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => 'First Lesson Watched'
        ]);
    }

    public function testFiveLessonsWatchedAchievement() {
        Event::fakeExcept([
            LessonWatched::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        
        $lessons = Lesson::factory()->count(4)->create();
        foreach ($lessons as $lesson) {
            $user->watched()->attach($lesson->id, ['watched' => true]);
        }

        $newLesson = Lesson::factory()->create();
        event(new LessonWatched($newLesson, $user));

        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => '5 Lessons Watched'
        ]);
    }

    public function testTenLessonsWatchedAchievement(){
        Event::fakeExcept([
            LessonWatched::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        
        $lessons = Lesson::factory()->count(9)->create();
        foreach ($lessons as $lesson) {
            $user->watched()->attach($lesson->id, ['watched' => true]);
        }

        $newLesson = Lesson::factory()->create();
        event(new LessonWatched($newLesson, $user));

        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => '10 Lessons Watched'
        ]);
    }

    public function testTwentyFiveLessonsWatchedAchievement(){
        Event::fakeExcept([
            LessonWatched::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        
        $lessons = Lesson::factory()->count(24)->create();
        foreach ($lessons as $lesson) {
            $user->watched()->attach($lesson->id, ['watched' => true]);
        }

        $newLesson = Lesson::factory()->create();
        event(new LessonWatched($newLesson, $user));

        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => '25 Lessons Watched'
        ]);
    }

    public function testFifthLessonsWatchedAchievement(){
        Event::fakeExcept([
            LessonWatched::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        
        $lessons = Lesson::factory()->count(49)->create();
        foreach ($lessons as $lesson) {
            $user->watched()->attach($lesson->id, ['watched' => true]);
        }

        $newLesson = Lesson::factory()->create();
        event(new LessonWatched($newLesson, $user));

        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => '50 Lessons Watched'
        ]);
    }

    public function testReceiveAnIntermediateBadgeFromLessonsNewAchievement(){
        Event::fakeExcept([
            LessonWatched::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        //set a pre-defined "static/fake" achivements quantity to the user
        $achievement = Achievement::factory()->count(3)->setUser($user)->create();

        $lessons = Lesson::factory()->count(49)->create();
        foreach ($lessons as $lesson) {
            $user->watched()->attach($lesson->id, ['watched' => true]);
        }

        //the user receive a new achievement after watched 50 lessons
        $newLesson = Lesson::factory()->create();
        event(new LessonWatched($newLesson, $user));

        $this->assertDatabaseHas('badges', [
            'user_id' => $user->id,
            'name' => 'Intermediate'
        ]);
    }

    public function testUserAchievementsEndpointWithAchievementThroughLessons() {
        Event::fakeExcept([
            LessonWatched::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        event(new LessonWatched($lesson, $user));

        $response = $this->getJson("/users/{$user->id}/achievements");

        $response->assertStatus(200)
                 ->assertJson([
                     'unlocked_achievements' => [
                        'First Lesson Watched'
                     ],
                     'next_available_achievements' => [
                        '5 Lessons Watched',
                        'First Comment Written'
                     ],
                     'current_badge' => 'Beginner',
                     'next_badge' => 'Intermediate', 
                     'remaing_to_unlock_next_badge' => 3
                 ]);
    }
    
}
