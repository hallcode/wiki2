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
        Schema::create("page_links", function (Blueprint $table) {
            $table->id();
            $table->foreignId("parent_page_id");
            $table->foreignId("target_page_id")->nullable();
            $table->string("link_text")->nullable();
            $table->boolean("target_exists");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("page_links");
    }
};
