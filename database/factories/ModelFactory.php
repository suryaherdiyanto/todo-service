<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Profile;
use App\Task;
use App\SubTask;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'password' => app('hash')->make('123123'),
        'is_verified' => 0,
    ];
});

$factory->define(Profile::class, function(Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'phone_number' => $faker->phoneNumber,
        'country' => $faker->country,
        'state' => $faker->state,
        'address' => $faker->address
    ];
});

$factory->define(Task::class, function(Faker $faker) {
    return [
        'title' => $faker->word,
        'deadline' => $faker->dateTimeThisYear,
        'note' => $faker->word,
        'is_completed' => $faker->randomElement([0, 1]),
        'progress' => $faker->randomNumber(2),
        'user_id' => factory(User::class)->create()->id
    ];
});

$factory->define(SubTask::class, function(Faker $faker) {
    return [
        'title' => $faker->word,
        'is_completed' => $faker->randomElement([0, 1]),
        'task_id' => factory(Task::class)->create()->id
    ];
});
