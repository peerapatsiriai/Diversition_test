<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotteriesTable extends Migration
{
    public function up()
    {
        Schema::create('lotteries', function (Blueprint $table) {
            $table->id();
            $table->string('prize1');
            $table->string('prize2_1');
            $table->string('prize2_2');
            $table->string('prize2_3');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lotteries');
    }
}
