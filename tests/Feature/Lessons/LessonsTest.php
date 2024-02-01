<?php 

namespace Tests\Feature\Lessons;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Models\Achievement;
use Database\Factories\AchievementFactory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Lesson;
use App\Events\LessonWatched;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class LessonsTest extends TestCase
{
    use RefreshDatabase;

    public function testFirstLessonWatchedAchievement() 
    {
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

    public function testFiveLessonsWatchedAchievement() 
    {
        //statically forcing the achievement of 5 comments
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

    public function testTenLessonsWatchedAchievement()
    {
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

    public function testTwentyFiveLessonsWatchedAchievement()
    {
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

    public function testFifthLessonsWatchedAchievement()
    {
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

    public function testReceiveAnIntermediateBadgeFromLessonsNewAchievement()
    {

        AchievementFactory::new()->resetCounters();

        Event::fakeExcept([
            LessonWatched::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $newUser = User::factory()->create();
        //set a pre-defined "static/fake" achivements quantity to the user
        $achievement = Achievement::factory()->count(3)->achievements()->setUser($newUser)->create();

        $lessons = Lesson::factory()->count(49)->create();
        foreach ($lessons as $lesson) {
            $newUser->watched()->attach($lesson->id, ['watched' => true]);
        }

        //the user receive a new achievement after watched 50 lessons
        $newLesson = Lesson::factory()->create();
        event(new LessonWatched($newLesson, $newUser));

        $this->assertDatabaseHas('badges', [
            'user_id' => $newUser->id,
            'name' => 'Intermediate'
        ]);
    }

    public function testReceiveAnAdvancedBadgeFromLessonsAndCommentsNewAchievements()
    {
        AchievementFactory::new()->resetCounters();

        Event::fakeExcept([
            CommentWritten::class,
            LessonWatched::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();

        //set a pre-defined "static/fake" comments achivements quantity to the user (respectively 1 and 3 comments achivements)
        $commentsAchievement =  Achievement::factory()->comments()->setUser($user)->count(3)->create();
        //geting a new achievement for making 5 comments
        for ($i=0; $i < 10; $i++) { 
            $comment = Comment::factory()->setUser($user)->create();
        }
        event(new CommentWritten($comment));
        
        //set a pre-defined "static/fake" lessons achivements quantity to the user (respectively 1,5,10,25 lessons)
        $lessonsAchievement = Achievement::factory()->count(3)->achievements()->setUser($user)->create();
        $lessons = Lesson::factory()->count(24)->create(); 
        foreach ($lessons as $lesson) {
            $user->watched()->attach($lesson->id, ['watched' => true]); //fake input of 24 lessons to the user
        }
        
        //the user receive a new achievement after watched 25 lessons
        $newLesson = Lesson::factory()->create();
        event(new LessonWatched($newLesson, $user));

        $this->assertDatabaseHas('badges', [
            'user_id' => $user->id,
            'name' => 'Advanced'
        ]);
    }

    public function testUserAchievementsEndpointWithAchievementThroughLessons() 
    {
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
