<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds columns to job_applications to distinguish and power "Job Hunter Mode"
     * (personal job hunting vs regular client applications) with AI-tailored
     * materials, status lifecycle, follow-ups, and notes.
     */
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Distinguish personal hunter applications from regular client applications
            $table->boolean('is_personal')->default(false)->after('userId')->index();

            // Personal hunting lifecycle stage (draft, applied, interviewing, offered, rejected, withdrawn)
            $table->string('hunter_status', 50)->nullable()->after('is_personal')->index();

            // Submission channel (e.g., website, greenhouse, email, linkedin, referral)
            $table->string('applied_channel', 100)->nullable()->after('hunter_status');

            // Actual date/time of submission
            $table->timestamp('applied_at')->nullable()->after('applied_channel');

            // Date for follow-up reminder
            $table->date('follow_up_at')->nullable()->after('applied_at');

            // Recruiter / hiring manager contact info
            $table->string('contact_info')->nullable()->after('follow_up_at');

            // Personal tracking notes
            $table->text('notes')->nullable()->after('contact_info');

            // AI-generated tailored materials for this specific application
            $table->string('suggested_subject_line')->nullable()->after('notes');
            $table->json('tailored_key_points')->nullable()->after('suggested_subject_line');
            $table->longText('tailored_cover_letter')->nullable()->after('tailored_key_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropIndex(['is_personal']);
            $table->dropIndex(['hunter_status']);

            $table->dropColumn([
                'is_personal',
                'hunter_status',
                'applied_channel',
                'applied_at',
                'follow_up_at',
                'contact_info',
                'notes',
                'suggested_subject_line',
                'tailored_key_points',
                'tailored_cover_letter',
            ]);
        });
    }
};
