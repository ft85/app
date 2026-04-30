<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class AddBifCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if BIF currency already exists
        $existing = DB::table('currencies')->where('code', 'BIF')->first();
        
        if (!$existing) {
            DB::table('currencies')->insert([
                'country' => 'Burundi',
                'currency' => 'Burundian Franc',
                'code' => 'BIF',
                'symbol' => 'FBu',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'created_at' => null,
                'updated_at' => null,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('currencies')->where('code', 'BIF')->delete();
    }
}
