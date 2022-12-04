<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColoringUserOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coloring_user_options', function (Blueprint $table) {
            $table->id();
            $table->integer('coloring_id');
            $table->integer('user_id');
            $table->string('img');
            $table->string('slug');
            $table->string('user_name');
            $table->string('age');
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
        Schema::dropIfExists('coloring_user_options');
    }
}
