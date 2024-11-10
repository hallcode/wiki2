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
        Schema::create("versions", function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->morphs("versionable");
            $table->foreignId("user_id");
            $table->text("content");
            $table->integer("word_count");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("versions");
    }
};
