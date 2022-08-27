<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class noteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notes=[];
        for($i=0;$i<100;$i++)
        {
            $n=[
            "title"=>"Title".$i,
            "description"=>"Description".$i,
            "user_id"=>($i%12)+1
            ];
            array_push($notes,$n);
        }
        DB::table('notes')->insert($notes);
    }
}
