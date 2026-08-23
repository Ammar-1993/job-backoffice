<?php

namespace App\Console\Commands;

use App\Services\JobImport\GreenhouseAdapter;
use App\Services\JobImport\JobImportService;
use App\Services\JobImport\WeWorkRemotelyAdapter;
use Illuminate\Console\Command;

class ImportExternalJobs extends Command
{
    protected $signature = 'jobs:import-external
        {--source=all : greenhouse|weworkremotely|all}
        {--limit=0 : أقصى عدد وظائف تُنشأ/تُحدَّث في هذا التشغيل (0 = بدون حد). كل وظيفة جديدة أو متغيّرة تُطلق استدعاء OpenAI حقيقي للـ embedding، فهذا الخيار يتحكم بالتكلفة أثناء الاختبار}';

    protected $description = 'يسحب وظائف من مصادر خارجية (Greenhouse، We Work Remotely) ويحقنها في job_vacancies';

    public function handle(): int
    {
        $source = $this->option('source');
        $limit = (int) $this->option('limit');

        $adapters = match ($source) {
            'greenhouse' => [new GreenhouseAdapter],
            'weworkremotely' => [new WeWorkRemotelyAdapter],
            default => [new GreenhouseAdapter, new WeWorkRemotelyAdapter],
        };

        $this->info($limit > 0 ? "بدء استيراد الوظائف (حد أقصى: {$limit})..." : 'بدء استيراد الوظائف...');

        $stats = (new JobImportService($adapters))->run($limit);

        $this->table(
            ['تم جلبها', 'جديدة', 'محدَّثة', 'متجاهَلة', 'فشلت'],
            [[$stats['fetched'], $stats['created'], $stats['updated'], $stats['skipped'], $stats['failed']]]
        );

        if ($stats['limited']) {
            $this->warn("⚠ توقف الاستيراد عند الحد الأقصى ({$limit}) — فيه وظائف من نتائج الجلب ما تمت معالجتها في هذا التشغيل. شغّل الأمر مرة ثانية أو ارفع --limit لمتابعة الباقي.");
        }

        return self::SUCCESS;
    }
}
