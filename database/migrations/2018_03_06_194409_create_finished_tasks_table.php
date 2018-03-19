<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinishedTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finished_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('test_id');
            $table->integer('right_answers_count');
            $table->integer('bad_answers_count');
            $table->integer('time_spent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finished_tasks');
    }
}
