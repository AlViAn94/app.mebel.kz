<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("frezers", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("order_id");
            $table->integer("user_id");
            $table->integer("status");
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists("frezers");
    }
};
