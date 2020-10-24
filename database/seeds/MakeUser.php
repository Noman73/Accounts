<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Information;
class MakeUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class,1)->create();
        factory(Information::class,1)->create();
    }
}
