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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('salary');
            $table->enum('type', ['Full-Time', 'Contract', 'Remote', 'Hybrid'])->default('Full-Time');
            $table->timestamps();
            $table->softDeletes();  
            
            $table->uuid('jobCategoryId');
            $table->foreign('jobCategoryId')->references('id')->on('job_categories')->onDelete('restrict');

            $table->uuid('companyId');
            $table->foreign('companyId')->references('id')->on('companies')->onDelete('restrict');

            $table->string('source_platform')->nullable();
            $table->string('source_url')->nullable()->unique();
            $table->string('external_id')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->integer('viewCount')->default(0);
            $table->longText('vector_embedding')->nullable();

            $table->index('title');
            $table->index('location');
            $table->index('companyId');
            $table->index('type');
            $table->index('source_platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
