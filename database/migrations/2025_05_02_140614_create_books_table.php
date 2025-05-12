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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->nullable();
            $table->string('isbn')->unique()->nullable();
            $table->string('epub_path');
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->string('year');
            $table->integer('pages');
            $table->string('language')->default('Bahasa Indonesia');
            $table->text('description')->nullable();
            $table->string('cover_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
