<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Teaser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TeaserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('teasers');

        $faker = Faker::create();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@coding.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role' => UserRole::ADMIN,
        ]);


        $users = collect([$admin]);

        for ($i = 0; $i < 5; $i++) {
            $users->push(User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]));
        }

        $placeholderImages = [
            'https://picsum.photos/id/1/1200/675',
            'https://picsum.photos/id/10/1200/675',
            'https://picsum.photos/id/100/1200/675',
            'https://picsum.photos/id/1000/1200/675',
            'https://picsum.photos/id/1002/1200/675',
            'https://picsum.photos/id/1015/1200/675',
            'https://picsum.photos/id/1018/1200/675',
            'https://picsum.photos/id/1019/1200/675',
            'https://picsum.photos/id/1021/1200/675',
            'https://picsum.photos/id/1022/1200/675',
        ];

        foreach ($users as $user) {
            $numTeasers = $user->id === $admin->id ? 10 : rand(2, 5);

            for ($i = 0; $i < $numTeasers; $i++) {
                $title = $faker->sentence(rand(3, 8));
                $slug = Str::slug($title);


                $count = 1;
                $originalSlug = $slug;
                while (Teaser::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }


                $imageUrl = $placeholderImages[array_rand($placeholderImages)];
                $imageName = Str::uuid() . '.jpg';

                try {
                    $imageContent = file_get_contents($imageUrl);
                    if ($imageContent) {
                        // Create the teaser first to get its ID
                        $teaser = Teaser::create([
                            'title' => $title,
                            'description' => $faker->paragraphs(rand(3, 6), true),
                            'slug' => $slug,
                            'image_name' => $imageName,
                            'user_id' => $user->id,
                            'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                            'updated_at' => now(),
                        ]);

                        // Now create the directory with the teaser ID
                        $path = 'teasers/' . $teaser->id;
                        Storage::disk('public')->makeDirectory($path);
                        $imagePath = $path . '/' . $imageName;

                        // Store the image
                        Storage::disk('public')->put($imagePath, $imageContent);
                    }
                } catch (\Exception $e) {
                    $this->command->warn(" can not update : " . $e->getMessage());

                    // Create the teaser without an image
                    Teaser::create([
                        'title' => $title,
                        'description' => $faker->paragraphs(rand(3, 6), true),
                        'slug' => $slug,
                        'image_name' => null,
                        'user_id' => $user->id,
                        'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('Teasers seeded successfully.');
    }
}
