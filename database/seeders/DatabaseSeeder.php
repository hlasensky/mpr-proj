<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Project;
use App\Models\Risk;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'role' => RoleEnum::Admin,
            'password' => bcrypt('password'),
        ]);

        $managers = User::factory(4)->create([
            'role' => RoleEnum::Manager,
        ]);

        $allUsers = $managers->prepend($admin);

        $allUsers->each(function (User $user) use ($allUsers) {
            Project::factory(3)
                ->for($user)
                ->create()
                ->each(function (Project $project) use ($allUsers) {
                    Risk::factory(5)
                        ->for($project)
                        ->create([
                            'user_id' => $allUsers->random()->id,
                        ]);
                });
        });
    }
}
