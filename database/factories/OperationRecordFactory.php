<?php

namespace Database\Factories\Pharaoh\OperationRecord\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Pharaoh\OperationRecord\Models\OperationRecord;

class OperationRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OperationRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'operator_id' => $this->faker->randomDigit,
            'operator_account' => $this->faker->word,
            'operator_name' => $this->faker->name,
            'ip' => $this->faker->ipv4,
            'func_key' => $this->faker->randomNumber(4),
            'func_id' => $this->faker->randomDigit,
            'targets' => $this->faker->name,
            'content' => $this->faker->sentence,
        ];
    }
}
