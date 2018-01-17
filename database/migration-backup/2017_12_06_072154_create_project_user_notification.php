<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectUserNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_user_notification', function (Blueprint $table) {
            $table->increments('pun_id');
            $table->integer('pun_project_id');
            $table->integer('pun_user_id');
            $table->text('pun_notification_key');
            $table->enum('pun_access', array('false', 'true'));
            $table->integer('pun_notification_assign_user_id');
            $table->timestamp('pun_timestamp');
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
