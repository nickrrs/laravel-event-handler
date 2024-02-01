<?php

namespace Tests\Feature\Comments;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use Database\Factories\AchievementFactory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use App\Events\CommentWritten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class CommentsTest extends TestCase {
    use RefreshDatabase;

    public function testFirstCommentAchievement() 
    {
        Event::fakeExcept([
            CommentWritten::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        $comment = Comment::factory()->setUser($user)->create();

        event(new CommentWritten($comment));

        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => 'First Comment Written'
        ]);
    }

    public function testThreeCommentsAchievement() 
    {
        //statically forcing the achievement of 3 comments
        Event::fakeExcept([
            CommentWritten::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        for ($i=0; $i < 3; $i++) { 
            $comment = Comment::factory()->setUser($user)->create();
        }

        event(new CommentWritten($comment));

        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => '3 Comments Written'
        ]);
    }

    public function testFiveCommentsAchievement() 
    {
        Event::fakeExcept([
            CommentWritten::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        for ($i=0; $i < 5; $i++) { 
            $comment = Comment::factory()->setUser($user)->create();
        }
        
        event(new CommentWritten($comment));
        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => '5 Comments Written'
        ]);
    }

    public function testTenCommentsAchievement() 
    {
        Event::fakeExcept([
            CommentWritten::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        for ($i=0; $i < 10; $i++) { 
            $comment = Comment::factory()->setUser($user)->create();
        }
        
        event(new CommentWritten($comment));
        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => '10 Comments Written'
        ]);
    }

    public function testTwentyCommentsAchievement() 
    {
        Event::fakeExcept([
            CommentWritten::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        for ($i=0; $i < 20; $i++) { 
            $comment = Comment::factory()->setUser($user)->create();
        }
        
        event(new CommentWritten($comment));
        $this->assertDatabaseHas('achievements', [
            'user_id' => $user->id,
            'name' => '20 Comments Written'
        ]);
    }
    public function testReceiveAnIntermediateBadgeFromCommentsNewAchievement()
    {

        AchievementFactory::new()->resetCounters();

        Event::fakeExcept([
            CommentWritten::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        //set a pre-defined "static/fake" comments achivements quantity to the user
        $achievement = Achievement::factory()->count(3)->comments()->setUser($user)->create();

        for ($i=0; $i < 10; $i++) { 
            $comment = Comment::factory()->setUser($user)->create();
        }
        event(new CommentWritten($comment));

        $this->assertDatabaseHas('badges', [
            'user_id' => $user->id,
            'name' => 'Intermediate'
        ]);
    }

    public function testUserAchievementsEndpointWithAchievementThroughComments() 
    {
        Event::fakeExcept([
            CommentWritten::class,
            AchievementUnlocked::class,
            BadgeUnlocked::class
        ]);

        $user = User::factory()->create();
        $comment = Comment::factory()->setUser($user)->create();

        event(new CommentWritten($comment));

        $response = $this->getJson("/users/{$user->id}/achievements");

        $response->assertStatus(200)
                 ->assertJson([
                     'unlocked_achievements' => [
                        'First Comment Written'
                     ],
                     'next_available_achievements' => [
                        'First Lesson Watched',
                        '3 Comments Written'
                     ],
                     'current_badge' => 'Beginner',
                     'next_badge' => 'Intermediate', 
                     'remaing_to_unlock_next_badge' => 3
                 ]);
    }
}