<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatingColumnUnitPriceBidItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('project_bid_items', function($table)
        // {
        //     $table->decimal('pbi_item_unit_price', 10,2)->change();
        // });
        DB::statement('ALTER TABLE `project_bid_items` CHANGE `pbi_item_unit_price` `pbi_item_unit_price` DECIMAL(10,2) NOT NULL;');
        DB::statement('ALTER TABLE `project_bid_items` CHANGE `pbi_item_total_price` `pbi_item_total_price` DECIMAL(10,2) NOT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
