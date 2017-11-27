<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyReportTableReplica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_daily_report_logs', function (Blueprint $table) {
            $table->increments('pdrl_id');
            $table->integer('pdr_report_id');
            $table->date('pdr_date');
            $table->text('pdr_weather')->nullable();
            $table->text('pdr_custom_field')->nullable();
            $table->enum('pdr_perform_work_day', array('yes', 'no'))->nullable();
            $table->enum('pdr_material_delivery', array('yes', 'no'))->nullable();
            $table->enum('pdr_milestone_completed', array('yes', 'no'))->nullable();
            $table->text('pdr_milestone_detail')->nullable();
            $table->text('pdr_occur_detail')->nullable();
            $table->text('pdr_general_notes')->nullable();
            $table->enum('pdr_picture_video', array('yes', 'no'))->nullable();
            $table->enum('pdr_sub_contractor_work', array('yes', 'no'));
            $table->text('pdr_sub_contractor_work_detail');
            $table->enum('pdr_status', array('incomplete', 'complete'));
            $table->integer('pdr_project_id');
            $table->integer('pdr_user_id')->nullable();
            $table->timestamp('pdr_timestamp');
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
