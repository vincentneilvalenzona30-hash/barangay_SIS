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
        Schema::create('blotters', function (Blueprint $table) {
            $table->id();
            // This links the blotter directly to a resident
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->string('incident_type'); // e.g., Theft, Noise Complaint, Dispute
            $table->date('date_of_incident');
            $table->text('narrative');
            $table->enum('status', ['Active', 'Settled', 'Scheduled for Hearing'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blotters');
    }
};
