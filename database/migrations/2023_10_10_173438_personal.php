<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("personal", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("full_name", 255);
            $table->char("iin", 12);
            $table->string("birsday", 255);
            $table->string("phone", 255);
            $table->string("email")->unique();
            $table->string("adres", 255);
            $table->integer("status");
            $table->string("position", 128);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("personal");
    }
};