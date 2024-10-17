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
    public function definition(): array {       
        $localisedFaker = Faker\Factory::create("uk_UA");     
        $name = fake()->firstName();
        $rand_image = "https://picsum.photos/70"; // Lorem Picsum api
        $image_source = file_get_contents($rand_image);
        if(!$image_source) $image_source = file_get_contents("public/images/default_user_photo.jpeg");
    
        return [
            'name'        => $name,
            'email'       => strtolower($name .'.' . fake()->unique()->lastName()) .'@'. fake()->safeEmailDomain(),      
            'phone'       => $localisedFaker->unique()->e164PhoneNumber(),        
            'photo'       => Users::uploadImage($image_source, true),
            'position_id' => fake()->randomElement(DB::table('positions')->pluck('id'))                       
        ];
        
    }

}
