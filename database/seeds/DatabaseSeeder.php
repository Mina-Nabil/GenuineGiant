<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      
        DB::table("dash_types")->insert([
            "DHTP_NAME" => "admin"
        ]);

        DB::table("dash_types")->insert([
            "DHTP_NAME" => "production"
        ]);

        DB::table("dash_types")->insert([
            "DHTP_NAME" => "sales"
        ]);

        DB::table('dash_users')->insert([
            "DASH_USNM" => "mina",
            "DASH_FLNM" => "Mina Nabil",
            "DASH_PASS" => bcrypt('mina@genuine'),           
            "DASH_TYPE_ID" => 1,
        ]);

        DB::table("categories")->insert([
            "CATG_NAME" => "Dogs",
            "CATG_ARBC_NAME" => "اكل كلاب",
        ]);

        DB::table("categories")->insert([
            "CATG_NAME" => "Cats",
            "CATG_ARBC_NAME" => "حريمي",
        ]);

        DB::table('order_status')->insert([
            "STTS_NAME" => "New"
        ]);
        DB::table('order_status')->insert([
            "STTS_NAME" => "Ready"
        ]);
        DB::table('order_status')->insert([
            "STTS_NAME" => "In Delivery"
        ]);
        DB::table('order_status')->insert([
            "STTS_NAME" => "Delivered"
        ]);
        DB::table('order_status')->insert([
            "STTS_NAME" => "Cancelled"
        ]);
        DB::table('order_status')->insert([
            "STTS_NAME" => "Returned"
        ]);

        DB::table("payment_options")->insert([
            "PYOP_NAME" => "Cash On Delivery",
            "PYOP_ARBC_NAME" => "كاش"
        ]);
    

        DB::table("payment_options")->insert([
            "PYOP_NAME" => "Credit Card",
            "PYOP_ARBC_NAME" => "بطاقه ائتمان"
        ]);

        DB::table("payment_options")->insert([
            "PYOP_NAME" => "Credit Card On Delivery",
            "PYOP_ARBC_NAME" => "بطاقه ائتمان عند التوصيل"
        ]);

        DB::table("delivery_slots")->insert([
            "DSLT_NAME" => "First Shift",
            "DSLT_STRT" => "09:00:00",
            "DSLT_END" => "12:00:00",
        ]);

        DB::table("delivery_slots")->insert([
            "DSLT_NAME" => "Second Shift",
            "DSLT_STRT" => "12:00:00",
            "DSLT_END" => "15:00:00",
        ]);
    }
}
