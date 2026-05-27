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
        Schema::create('news_events', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['News', 'Event'])->default('News');
            $table->string('title');
            $table->string('tag')->nullable();
            $table->string('date_label')->nullable();
            $table->string('schedule')->nullable();
            $table->string('location')->nullable();
            $table->text('description');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_events');
    }
};
