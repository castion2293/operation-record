<?php

namespace Database\Factories\Pharaoh\OperationRecord\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
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
            'subject_id' => $this->faker->randomDigit,
            'func_key' => $this->faker->randomNumber(4),
            'ip' => $this->faker->ipv4,
        ];
    }
}
