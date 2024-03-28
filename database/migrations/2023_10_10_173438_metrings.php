<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('test')->create("metrings", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("order_id");
            $table->string("file", 255);
            $table->integer("user_id");
            $table->integer("status");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("metrings");
    }
};
