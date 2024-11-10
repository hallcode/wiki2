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
        Schema::create("media", function (Blueprint $table) {
            $table->id();
            $table->string("title")->unique();
            $table->string("mime_type", 30);
            $table->string("directory");
            $table->integer("size")->nullable();
            $table->string("filename")->unique();
            $table->foreignId("user_id");
            $table->foreignUlid("version_id")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create("media_page", function (Blueprint $table) {
            $table->integer("media_id");
            $table->integer("page_id");

            $table->primary(["media_id", "page_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("media");
    }
};
