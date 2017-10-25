<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesPermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function(Blueprint $table){
            $table->increment('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function(Blueprint $table){
            $table->increment('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_role', function(Blueprint $table){
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            
            $table->foreign('role_id')
                ->reference('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->reference('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['role_id', 'permission_id'])
        });

        Schema::create('role_user', function(Blueprint $table){
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();
            
            $table->foreign('role_id')
                ->reference('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->reference('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary(['role_id', 'user_id'])
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
