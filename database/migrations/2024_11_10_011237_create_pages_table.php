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
        Schema::create("pages", function (Blueprint $table) {
            $table->id();
            $table->string("title", 255)->unique();
            $table->foreignId("user_id");
            $table->foreignUlid("version_id")->nullable();
            $table->foreignId("redirect_to")->nullable();
            $table->foreignId("type_id")->nullable();
            $table->boolean("is_locked")->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("pages");
    }
};
