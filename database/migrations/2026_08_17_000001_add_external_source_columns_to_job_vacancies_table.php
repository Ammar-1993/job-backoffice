<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds the columns needed to track job_vacancies rows that were
     * pulled in automatically by the import pipeline (Greenhouse,
     * We Work Remotely, ...) instead of being posted natively by a
     * Company Owner through the backoffice.
     */
    public function up(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            // e.g. "greenhouse", "weworkremotely" — null for natively-posted jobs
            $table->string('source_platform')->nullable()->after('companyId');

            // the original listing URL — used to de-duplicate on re-import
            $table->string('source_url')->nullable()->unique()->after('source_platform');

            // platform-specific job id, when the source provides one
            $table->string('external_id')->nullable()->after('source_url');

            $table->timestamp('imported_at')->nullable()->after('external_id');

            $table->index('source_platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropUnique(['source_url']);
            $table->dropIndex(['source_platform']);
            $table->dropColumn(['source_platform', 'source_url', 'external_id', 'imported_at']);
        });
    }
};
