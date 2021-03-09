<?php

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
        // $this->call(UsersTableSeeder::class);
        //factory(App\User::class, 1000)->create();
        /*
        //factory(App\User::class, 100)->create()->each(function ($user) {
            if($user->register_kind === 2){
                factory(App\SellerInfo::class)->create(['uid' => $user->id]);
            }
        //});
        */
        //factory(App\Category::class)->create();
        //$items = factory(App\Item::class, 10)->create();
        //factory(App\ItemOption::class)->create(['items' => $items]);
        factory(App\Item::class, 10)->create()->each(function ($item) {
            $colors = ['빨강', '노랑', '파랑'];
            $sizes = ['S', 'M', 'L', 'XL'];

            for($i = 0; $i<3; $i++){
                for($j = 0; $j < 4; $j++){
                    factory(App\ItemOption::class)->create(['item_id' => $item->item_id, 'name' => $colors[$i].','.$sizes[$j]]);
                }
            }
        });
        //App\User::create($users);




    }
}
