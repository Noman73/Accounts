<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Voucer;
use App\Information;
use App\Customer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

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

$factory->define(Voucer::class, function (Faker $faker){
    return [
        'bank_id' => 1,
        'dates' => strtotime(date('d-m-Y')),
        'name' => 'customer',
        'name_data_id' => 19,
        'payment_type'=>Arr::random(['Deposit','Expence']),
        'ammount'=>rand(100,1000),
        'user_id'=>1,
        'micro_time'=>explode(' ',microtime())[1].'.'.(int)round(explode(' ',microtime())[0]*1000),
    ];
});

$factory->define(Information::class, function (Faker $faker){
    return [
        'company_name' => 'Noman Enterprize',
        'company_slogan' => 'accounts for all',
        'country' => 'Bangladesh',
        'adress' => 'barisal,Bangladesh',
        'phone' => '01823767347',
        'logo'=>'fixed.jpg',
    ];
});

$factory->define(Customer::class, function (Faker $faker){
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'phone1' => $faker->phoneNumber,
        'adress' => $faker->address,
        'stutus' =>1,
    ];
});