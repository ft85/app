<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('products', 'injonge_code')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('injonge_code')->nullable()->after('sku');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('products', 'injonge_code')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('injonge_code');
            });
        }
    }
};
