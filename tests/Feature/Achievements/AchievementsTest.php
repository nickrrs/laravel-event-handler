<?php

namespace Tests\Feature\Achievements;

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class AchievementsTest extends TestCase {
    use RefreshDatabase;

    public function testFailToUnlockNonexistentAchievement()
    {
        $user = User::factory()->create();

        $lessons = Lesson::factory()->count(3)->create();
        foreach ($lessons as $lesson) {
            $user->watched()->attach($lesson->id, ['watched' => true]);
        }

        $newLesson = Lesson::factory()->create();
        event(new LessonWatched($newLesson, $user));

        $unexpectedAchievement = 'achievement that doesnt exists';
        $this->assertDatabaseMissing('achievements', [
            'user_id' => $user->id,
            'name' => $unexpectedAchievement
        ]);
    }

    public function testAccessingAchievementsForNonexistentUser()
    {
        $nonexistentUserId = 999; 
        $response = $this->getJson("/users/{$nonexistentUserId}/achievements");

        $response->assertStatus(404);
    }

    public function testNoAchievementsUnlocked()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/users/{$user->id}/achievements");

        $response->assertStatus(200)
        ->assertJson([
            'unlocked_achievements' => [],
            'next_available_achievements' => ['First Lesson Watched','First Comment Written'], 
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 4 
        ]);
    }
}