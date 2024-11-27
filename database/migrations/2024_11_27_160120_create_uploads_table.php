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
        Schema::create("uploads", function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->string("file_name")->unique();
            $table->foreignId("media_id");
            $table->string("path")->unique();
            $table->string("mime_type", 30);
            $table->string("type");
            $table->integer("size")->nullable();
            $table->foreignId("user_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("uploads");
    }
};
