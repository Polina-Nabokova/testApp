<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Faker;
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
        $localisedFaker = Faker\Factory::create("uk_UA");     
        $name = fake()->firstName();
        $image = fake()->image( public_path('images\tmp'), 70, 70, '', false, true,'photo', false, 'jpeg');
        Users::compressImage($image);
        $image = 'images/users/'. $image;       
        return [
            'name'        => $name,
            'email'       => strtolower($name .'.' . fake()->unique()->lastName()) .'@'. fake()->safeEmailDomain(),      
            'phone'       => $localisedFaker->unique()->e164PhoneNumber(),        
            'photo'       => $image,
            'position_id' => fake()->randomElement(DB::table('positions')->pluck('id'))                       
        ];
        
    }

}
