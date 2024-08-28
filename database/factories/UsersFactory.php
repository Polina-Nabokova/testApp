<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Users;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users>
 */
class UsersFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->name(),
            'email'       => fake()->unique()->safeEmail(),      
            'phone'       => '+380' . fake()->unique()->numerify('#######'),        
            'photo'       => 'images/'. fake()->image($dir = public_path('images'), $width = 70, $height = 70, $category = null, $fullPath = false, $randomize = true, $word = 'user_photo', $gray = false, $format = 'png'),
            'position_id' => fake()->randomElement(DB::table('positions')->pluck('id'))                       
        ];
        
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
   // public function unverified(): static
   // {
   //     return $this->state(fn (array $attributes) => [
   //         'email_verified_at' => null,
   //     ]);
   // }
}
