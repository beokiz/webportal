<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\Training;
use App\Models\User;

/**
 * Training Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 * @package Database\Factories
 */
class TrainingFactory extends BaseFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        $multiplier = User::inRandomOrder()->whereHas('roles', function ($query) {
            $query->where('name', config('permission.project_roles.user_multiplier'));
        })->first();

        $participantCount = $this->faker->randomNumber(2);

        return [
            'multi_id'                       => !empty($multiplier) ? $multiplier->id : null,
            'first_date'                     => $this->faker->date,
            'first_date_start_and_end_time'  => $this->faker->time,
            'second_date'                    => $this->faker->date,
            'second_date_start_and_end_time' => $this->faker->time,
            'location'                       => "{$this->faker->streetName} {$this->faker->randomNumber()}, {$this->faker->city}",
            'max_participant_count'          => $participantCount + $this->faker->randomNumber(2),
            'participant_count'              => $participantCount,
            'type'                           => $this->faker->randomElement(Training::TYPES),
            'status'                         => $this->faker->randomElement(Training::STATUSES),
            'notes'                          => rand(0, 1) ? $this->faker->sentence : null,
        ];
    }
}
