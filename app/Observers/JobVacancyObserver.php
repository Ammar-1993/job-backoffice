<?php

namespace App\Observers;

use App\Models\JobVacancy;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class JobVacancyObserver
{
    /**
     * Handle the JobVacancy "created" event.
     * Generates a vector embedding for every new job vacancy if not already set.
     */
    public function created(JobVacancy $jobVacancy): void
    {
        $this->ensureEmbedding($jobVacancy);
    }

    /**
     * Handle the JobVacancy "updated" event.
     * Re-generates the embedding when core content fields change.
     */
    public function updated(JobVacancy $jobVacancy): void
    {
        $contentChanged = $jobVacancy->wasChanged(['title', 'description', 'location', 'type']);

        if ($contentChanged || empty($jobVacancy->vector_embedding)) {
            $this->ensureEmbedding($jobVacancy);
        }
    }

    /**
     * Generate and persist a vector embedding for the given job vacancy.
     */
    private function ensureEmbedding(JobVacancy $jobVacancy): void
    {
        if (!empty($jobVacancy->vector_embedding) && strlen((string) $jobVacancy->vector_embedding) > 100) {
            return; // Already has a valid embedding – skip.
        }

        try {
            $text = json_encode([
                'title'       => $jobVacancy->title,
                'description' => $jobVacancy->description,
                'location'    => $jobVacancy->location,
                'type'        => $jobVacancy->type,
            ]);

            $response = OpenAI::embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $text,
            ]);

            // Use forceFill + saveQuietly to bypass $fillable guards and
            // avoid triggering another observer loop.
            $jobVacancy->forceFill([
                'vector_embedding' => json_encode($response->embeddings[0]->embedding),
            ])->saveQuietly();

            Log::info("JobVacancyObserver: Generated embedding for JobVacancy ID {$jobVacancy->id}");
        } catch (\Exception $e) {
            Log::error("JobVacancyObserver: Failed to generate embedding for JobVacancy ID {$jobVacancy->id} — {$e->getMessage()}");
        }
    }
}
