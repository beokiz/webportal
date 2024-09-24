<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace Database\Factories;

use App\Models\TrainingProposal;
use App\Models\User;

/**
 * TrainingProposal Factory
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingProposal>
 * @package Database\Factories
 */
class TrainingProposalFactory extends BaseFactory
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

        return [
            'multi_id'          => !empty($multiplier) ? $multiplier->id : null,
            'first_date'        => $this->faker->date,
            'second_date'       => $this->faker->date,
            'location'          => "{$this->faker->streetName} {$this->faker->randomNumber()}, {$this->faker->city}",
            'street'            => $this->faker->streetName,
            'house_number'      => $this->faker->randomNumber(),
            'zip_code'          => $this->faker->randomNumber(6),
            'city'              => $this->faker->city,
            'participant_count' => $this->faker->randomNumber(2),
            'status'            => $this->faker->randomElement(TrainingProposal::STATUSES),
            'notes'             => rand(0, 1) ? $this->faker->sentence : null,
        ];
    }
}
