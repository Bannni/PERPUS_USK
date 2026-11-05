<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('category')->default('General');
            $table->enum('shelf_section', ['currently_reading', 'next_up', 'finished'])->default('next_up');
            $table->string('cover_url')->nullable();
            $table->decimal('rating', 2, 1)->default(4.5);
            $table->integer('price')->default(75000);
            $table->integer('pages')->default(250);
            $table->integer('reading_progress')->default(0);
            $table->text('synopsis')->nullable();
            $table->integer('published_year')->nullable();
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
