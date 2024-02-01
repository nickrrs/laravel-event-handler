<?php

namespace Database\Factories;

use App\Enums\AchievementsEnum;
use App\Enums\CommentsAchievementEnum;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class AchievementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Achievement::class;
    private static $achievementsCount = 0;
    private static $commentsCount = 0;
    private static $enumType = 'achievement';
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (self::$enumType === 'achievement') {
            $values = AchievementsEnum::cases();
            $index = min(self::$achievementsCount++, count($values) - 1);
        } else {
            $values = CommentsAchievementEnum::cases();
            $index = min(self::$commentsCount++, count($values) - 1);
        }

        $value = $values[$index]->value;

        return [
            'name' => $value,
            'user_id' => User::factory(),
        ];
    }

    public function resetCounters()
    {
        self::$achievementsCount = 0;
        self::$commentsCount = 0;
        return $this; // Retorna a instância da factory
    }

    public function achievements()
    {
        self::$enumType = 'achievement';
        return $this;
    }

    public function comments()
    {
        self::$enumType = 'comment';
        return $this;
    }

    public function setUser(User $user)
    {
        return $this->state([
            'user_id' => $user->id,
        ]);
    }
}
