<?php

use App\Attendee;
use App\Organizer;
use App\Session;
use App\Speaker;
use App\Speaker_session;
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
//        Organizer::where('id', '01')->first()
//            ->update([
//                'password_hash' => bcrypt('demopass1')
//            ]);
//
//        Organizer::where('id', '02')->first()
//            ->update([
//                'password_hash' => bcrypt('demopass2')
//            ]);

        foreach (Attendee::all() as $value)
            $value->update([
                'password' => bcrypt("123")
            ]);

//        foreach (Session::all() as $item) {
//            Speaker_session::create([
//                'session_id' => $item->id,
//                'speaker_id' => '1'
//            ]);
//        }
    }
}
