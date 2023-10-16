<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("users", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->char("iin", 12);
            $table->string("name", 255);
            $table->string("phone", 255);
            $table->string("email")->unique();
            $table->timestamp("email_verified_at");
            $table->string("password", 255);
            $table->string("position", 255);
            $table->string("remember_token", 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("users");
    }
};