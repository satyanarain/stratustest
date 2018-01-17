<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectPreliminaryLienReleaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_preliminary_lien_release', function (Blueprint $table) {
            $table->increments('pplr_id');
            $table->integer('pplr_project_id');
            $table->integer('pplr_preliminary_id');
            $table->date('date_of_billed_through');
            $table->string('lien_release_note');
            $table->integer('pplr_user_id');
            $table->integer('lien_release_path');
            $table->enum('pplr_type', array('partial', 'full'));
            $table->timestamp('added_on');
        });
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
