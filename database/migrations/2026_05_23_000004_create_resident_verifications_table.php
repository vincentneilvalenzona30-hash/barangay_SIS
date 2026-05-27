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
        Schema::create('resident_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('gender');
            $table->string('civil_status');
            $table->string('contact_number');
            $table->string('address');
            $table->string('purok')->nullable();
            $table->string('years_of_residency')->nullable();
            $table->string('valid_id_type');
            $table->string('valid_id_number')->nullable();
            $table->string('valid_id_path');
            $table->string('face_photo_path');
            $table->enum('status', ['Draft', 'Submitted', 'Verified', 'Rejected'])->default('Submitted');
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_verifications');
    }
};
