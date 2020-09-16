<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rating;

class RatingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
        $ratings = Rating::factory()->count(300)->create();
        }catch(\Exception $ex)
        {
            if($ex->getCode()===23000){

            }
        }
    }
}
