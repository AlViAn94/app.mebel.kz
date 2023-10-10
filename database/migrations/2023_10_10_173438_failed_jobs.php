<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("failed_jobs", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("uuid", 255);
            $table->text("connection");
            $table->text("queue");
            $table->longtext("payload");
            $table->longtext("exception");
            $table->timestamp("failed_at");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("failed_jobs");
    }
};