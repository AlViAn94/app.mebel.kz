<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('test')->create("orders", function (Blueprint $table) {
            $table->unsignedBigInteger("id");
            $table->bigInteger("client_id");
            $table->string("order_num", 255);
            $table->string("address", 255);
            $table->integer("sum");
            $table->string("type", 255);
            $table->integer("status");
            $table->text("comment");
            $table->string("date_end", 255);
            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients');

            $table->foreign('order_id')
                ->references('id')
                ->on('jobs');

            $table->foreign('order_id')
                ->references('id')
                ->on('technologists');

            $table->foreign('design')
                ->references('id')
                ->on('design');

            $table->foreign('jobs')
                ->references('id')
                ->on('metrings');
        });
    }

    public function down()
    {
        Schema::dropIfExists("orders");
    }
};
