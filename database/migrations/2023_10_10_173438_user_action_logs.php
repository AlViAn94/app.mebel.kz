<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("user_action_logs", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("name", 255);
            $table->integer("user_id");
            $table->text("action_description");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("user_action_logs");
    }
};