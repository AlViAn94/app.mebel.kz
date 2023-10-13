<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("user_role", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->integer("user_id");
            $table->string("role", 255);
            $table->string("name", 128);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("user_role");
    }
};