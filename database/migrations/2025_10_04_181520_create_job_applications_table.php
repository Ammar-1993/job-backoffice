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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'pending_analysis'])->default('pending')->nullable();
            $table->float('aiGeneratedScore', 2)->default(0);
            $table->timestamps();
            $table->longText('aiGeneratedFeedback')->nullable();
            $table->softDeletes();

            $table->uuid('jobVacancyId');
            $table->foreign('jobVacancyId')->references('id')->on('job_vacancies')->onDelete('restrict');

            $table->uuid('resumeId');
            $table->foreign('resumeId')->references('id')->on('resumes')->onDelete('restrict');

            $table->uuid('userId');
            $table->foreign('userId')->references('id')->on('users')->onDelete('restrict');

            $table->boolean('is_personal')->default(false)->index();
            $table->string('hunter_status', 50)->nullable()->index();
            $table->string('applied_channel', 100)->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->date('follow_up_at')->nullable();
            $table->string('contact_info')->nullable();
            $table->text('notes')->nullable();
            $table->string('suggested_subject_line')->nullable();
            $table->json('tailored_key_points')->nullable();
            $table->longText('tailored_cover_letter')->nullable();
        });

           
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
