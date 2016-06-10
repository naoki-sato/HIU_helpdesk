<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\LostItem;

class LostItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 削除
        DB::table('lost_items')->truncate();

        $faker = Faker::create('ja_JP');
        for ($i=0; $i < 300; $i++) { 
            LostItem::create([
                'lost_item_name' => $faker->word,
                'reciept_staff_id' => $faker->randomElement([1,2,3]),
                'place_id' => $faker->randomElement([1,2,3,4,5,6,7,8,9]),
                'created_at' => Carbon::now()->subYears(1),
                'updated_at' => Carbon::now()->subYears(1),
                'note' => $faker->text,

            ]);
        }
        for ($i=0; $i < 300; $i++) { 
            LostItem::create([
                'lost_item_name' => $faker->word,
                'reciept_staff_id' => $faker->randomElement([1,2,3]),
                'place_id' => $faker->randomElement([1,2,3,4,5,6,7,8,9]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'note' => $faker->text,

            ]);
        }
    }
}
