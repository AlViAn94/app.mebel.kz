<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('test')->create("jobs", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("order_id");
            $table->integer("user_id");
            $table->string("position", 256);
            $table->integer("status");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("jobs");
    }
};
