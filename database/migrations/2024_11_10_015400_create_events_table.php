<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("events", function (Blueprint $table) {
            $table->id();
            $table->string("title", 255);
            $table->foreignId("page_id")->nullable();
            $table->foreignId("user_id");
            $table->date("date");
            $table->string("type")->nullable();
            $table->foreignUlid("version_id")->nullable();
            $table->boolean("show_on_page")->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("events");
    }
};
