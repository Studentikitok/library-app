<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('cover_image')->nullable();
            $table->string('isbn')->unique();
            $table->text('description')->nullable();
            $table->string('language');
            $table->enum('status', ['available', 'reserved'])->default('available');
        });
    }

    public function down(): void
    {  
        Schema::dropIfExists('books');
    }
};
