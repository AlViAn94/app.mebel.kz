<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("clients", function (Blueprint $table) {
            $table->unsignedBigInteger("id");
            $table->char("iin", 12);
            $table->string("name", 128);
            $table->string("surname", 128);
            $table->string("lastname", 128);
            $table->string("phone", 255);
            $table->string("email")->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("clients");
    }
};
