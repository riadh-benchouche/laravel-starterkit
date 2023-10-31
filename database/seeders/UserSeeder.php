<?php

namespace Database\Seeders;


use App\Enums\UserGenders;
use App\Enums\UserRoles;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;


class UserSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        DB::table('users')->truncate();

        User::withoutEvents(function () {
            $this->CreateUser(UserRoles::ROOT->value);
            $this->CreateUser(UserRoles::ADMINISTRATOR->value);
            $this->CreateUser(UserRoles::USER->value);
        });
    }

    /**
     * @param string $role
     * @return void
     * @throws Exception
     */
    private function CreateUser(string $role): void
    {
        $faker = \Faker\Factory::create();
        User::create([
            'email' => Str::lower($role) . '@' . Str::snake(Str::lower(config('app.name', 'fstck')), '-') . '.com',
            'password' => '123456',
        ])->assignRole($role)
            ->profile()
            ->create(
                [
                    'first_name' => Str::lower($role),
                    'last_name' => Str::snake(config('app.name', 'fstck'), '-'),
                    'phone_number' => $faker->phoneNumber,
                    'address' => 'Ivry sur Seine',
                    'dob' => Carbon::now()->subYears(random_int(20, 40)),
                    'gender' => UserGenders::WOMAN->value,
                ]
            );
    }
}
