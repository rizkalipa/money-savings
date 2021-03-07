<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_histories', function (Blueprint $table) {
            $table->id();
            $table->double('amount');
            $table->enum('type', ['expense', 'revenue']);
            $table->text('note')->nullable();
            $table->text('saving_rate');
            $table->text('remaining_target');
            $table->text('total_amount');
            $table->smallInteger('is_increase');
            $table->unsignedBigInteger('savings_id');
            $table->foreign('savings_id')->references('id')->on('savings');
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
        Schema::dropIfExists('saving_histories');
    }
}
