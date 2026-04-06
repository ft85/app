<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnspecTable extends Migration
{
    public function up()
    {
        Schema::create('unspec', function (Blueprint $table) {
            $table->id();
            $table->string('unspec_code')->unique();
            $table->string('item_class');
            $table->timestamps();
        });

        // Add unspec column to categories if it doesn't exist
        if (!Schema::hasColumn('categories', 'unspec')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('unspec')->nullable()->after('description');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('unspec');

        if (Schema::hasColumn('categories', 'unspec')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('unspec');
            });
        }
    }
}
