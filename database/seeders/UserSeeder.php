<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Users\Entities\UserKey;
use Modules\Users\Entities\Users;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'name' => "Super Admin",
            'email' => "superadmin@example.com",
            'email_verified_at' => now(),
            'password' => '$2y$10$HhsLrOJdheCeH.zfSTYXVea9tUE5KGtBwqwoB9gBIF5qjvr7IldNK', // temp
            'remember_token' => Str::random(10),
        ];

        User::updateOrCreate([
            'email' => $user['email']
        ], $user);
    }
}
