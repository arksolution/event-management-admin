<?php

use App\Organizer;
use App\Session;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Organizer::where('id', '01')->first()
            ->update([
                'password_hash' => bcrypt('demopass1')
            ]);

        Organizer::where('id', '02')->first()
            ->update([
                'password_hash' => bcrypt('demopass2')
            ]);
    }
}
