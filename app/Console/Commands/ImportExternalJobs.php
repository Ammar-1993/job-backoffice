<?php

namespace App\Console\Commands;

use App\Services\JobImport\AdzunaAdapter;
use App\Services\JobImport\GreenhouseAdapter;
use App\Services\JobImport\JobImportService;
use App\Services\JobImport\RemotiveAdapter;
use App\Services\JobImport\WeWorkRemotelyAdapter;
use Illuminate\Console\Command;

class ImportExternalJobs extends Command
{
    protected $signature = 'jobs:import-external
        {--source=all : greenhouse|weworkremotely|adzuna|remotive|all}
        {--limit=0 : أقصى عدد وظائف تُنشأ/تُحدَّث في هذا التشغيل (0 = بدون حد)}
        {--min-match=0 : الحد الأدنى لنسبة التطابق مع سيرة المرشح (مثلاً 80). الوظائف التي تقل عن هذا الحد تُلغى فوراً ولا يتم استيرادها}
        {--email=ammaralnggar@gmail.com : بريد المرشح المعتمد للمطابقة أثناء الاستيراد}';

    protected $description = 'يسحب وظائف من مصادر خارجية (Greenhouse، We Work Remotely، Adzuna GCC، Remotive) ويحقنها في job_vacancies';

    public function handle(): int
    {
        $source   = $this->option('source');
        $limit    = (int) $this->option('limit');
        $minMatch = (int) $this->option('min-match');

        $candidateSkills = null;
        $resumeEmbedding = null;

        if ($minMatch > 0) {
            $email = $this->option('email');
            $user = \App\Models\User::where('email', $email)->first() ?? \App\Models\User::where('role', 'job_seeker')->first();
            $resume = $user?->activeResume ?? $user?->resumes()->latest()->first();
            if ($resume) {
                $candidateSkills = $resume->skills ?? [];
                $resumeEmbedding = $resume->vector_embedding ? json_decode($resume->vector_embedding, true) : null;
                $this->info("🎯 تفعيل فلتر الاستيراد الصارم: إبقاء الوظائف المطابقة لـ {$user->name} بنسبة >= {$minMatch}% فقط، وتخطي الباقي.");
            } else {
                $this->warn("لم يتم العثور على سيرة ذاتية للمستخدم {$email}. سيتم الاستيراد بدون فلتر التطابق.");
                $minMatch = 0;
            }
        }

        $adapters = match ($source) {
            'greenhouse'     => [new GreenhouseAdapter],
            'weworkremotely' => [new WeWorkRemotelyAdapter],
            'adzuna'         => [new AdzunaAdapter],
            'remotive'       => [new RemotiveAdapter],
            default          => [new GreenhouseAdapter, new WeWorkRemotelyAdapter, new AdzunaAdapter, new RemotiveAdapter],
        };

        $this->info($limit > 0 ? "بدء استيراد الوظائف (حد أقصى: {$limit})..." : 'بدء استيراد الوظائف...');

        $stats = (new JobImportService($adapters))->run($limit, $minMatch, $candidateSkills, $resumeEmbedding);

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
