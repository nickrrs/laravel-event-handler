<?php

namespace Database\Factories;

use App\Enums\AchievementsEnum;
use App\Enums\CommentsEnum;
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

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $enumValues = array_merge(
            AchievementsEnum::cases(), 
            CommentsEnum::cases()
        );
        $enumValues = array_map(fn($enum) => $enum->value, $enumValues);

        return [
            'name' => $this->faker->randomElement($enumValues),
            'user_id' => User::factory(),
        ];
    }


    public function setUser(User $user)
    {
        return $this->state([
            'user_id' => $user->id,
        ]);
    }
}
