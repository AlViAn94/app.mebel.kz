<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('test')->create("orders_type", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("name", 128);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("orders_type");
    }
};
