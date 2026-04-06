<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissingObrTables extends Migration
{
    public function up()
    {
        // OBR stock movement log
        if (!Schema::hasTable('stockmaster_obr')) {
            Schema::create('stockmaster_obr', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('bhfid', 20)->nullable();
                $table->longText('request')->nullable();
                $table->tinyInteger('sent_obr')->default(0);
                $table->string('url')->nullable();
                $table->timestamps();
            });
        }

        // OBR registered devices
        if (!Schema::hasTable('devices')) {
            Schema::create('devices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('business_id')->nullable();
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->string('serial')->nullable()->index();
                $table->tinyInteger('enabled')->default(1);
                $table->timestamps();
            });
        }

        // OBR stock items synced from EBMS
        if (!Schema::hasTable('injonge_items')) {
            Schema::create('injonge_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->string('item_code')->nullable();
                $table->string('item_designation')->nullable();
                $table->decimal('quantity', 15, 4)->default(0);
                $table->string('measurement_unit')->nullable();
                $table->decimal('purchase_price', 15, 4)->default(0);
                $table->decimal('sale_price', 15, 4)->default(0);
                $table->string('currency', 10)->nullable();
                $table->timestamps();
            });
        }

        // EUCL electricity token ledger
        if (!Schema::hasTable('account_eucl')) {
            Schema::create('account_eucl', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contact_id')->nullable()->index();
                $table->decimal('debit', 15, 4)->default(0);
                $table->decimal('credit', 15, 4)->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Balance view fallback table (vwbalance may be a DB view; table ensures no crash)
        if (!Schema::hasTable('vwbalance')) {
            Schema::create('vwbalance', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contact_id')->nullable()->index();
                $table->decimal('balance', 15, 4)->default(0);
                $table->timestamps();
            });
        }

        // stock_adjustments core table (if missing)
        if (!Schema::hasTable('stock_adjustments')) {
            Schema::create('stock_adjustments', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('business_id');
                $table->unsignedInteger('location_id');
                $table->unsignedInteger('transaction_id')->nullable();
                $table->string('ref_no')->nullable();
                $table->date('date');
                $table->unsignedInteger('adjusted_by');
                $table->text('note')->nullable();
                $table->decimal('final_total', 15, 4)->default(0);
                $table->string('status')->default('completed');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('stockmaster_obr');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('injonge_items');
        Schema::dropIfExists('account_eucl');
        Schema::dropIfExists('vwbalance');
        // Do not drop stock_adjustments on rollback — it's a core table
    }
}
