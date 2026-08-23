<?php

namespace App\Services\JobImport;

interface JobSourceAdapter
{
    /**
     * Fetch listings from the source and return them normalized.
     *
     * Each item MUST have these keys:
     *   title, description, location, type, salary, company_name,
     *   source_platform, source_url, external_id
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetch(): array;
}
