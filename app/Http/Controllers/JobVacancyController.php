<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use App\Models\Company;
use App\Models\JobCategory;
use App\Http\Requests\JobVacancyCreateRequest;
use App\Http\Requests\JobVacancyUpdateRequest;
use App\Enums\JobType;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Active
        $query = JobVacancy::latest();

        // Scope the query for company_owner role
        if (auth()->user()->role == 'company_owner') {
            $company = auth()->user()->company;
            if (! $company) {
                // No company attached to this user — return no results
                $query->whereRaw('0 = 1');
            } else {
                $query->where('companyId', $company->id);
            }
        }

        // Eager-load company (include soft-deleted companies) and category
        $query->with([
            'company' => function($q) { $q->withTrashed(); },
            'jobCategory'
        ]);

        // Handle Search query: Search by title, company name, or category name
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                // 1. Search by job title
                $q->where('title', 'like', '%' . $search . '%')
                  // 2. Search by company name (relationship)
                  ->orWhereHas('company', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  // 3. Search by category name (relationship)
                  ->orWhereHas('jobCategory', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Archived
        if ($request->input('archived') == 'true') {
            $query->onlyTrashed();
        }

        $jobVacancies = $query->paginate(10)->onEachSide(1);
        
        // Pass the search term back to the view
        return view('job-vacancy.index', compact('jobVacancies', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        $jobCategories = JobCategory::all();
        $jobTypes = JobType::cases();
        return view('job-vacancy.create', compact('companies', 'jobCategories', 'jobTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobVacancyCreateRequest $request)
    {
        $validated = $request->validated();
        
        try {
            $textToEmbed = json_encode([
                'title' => $validated['title'] ?? '',
                'description' => $validated['description'] ?? '',
                'location' => $validated['location'] ?? '',
                'type' => $validated['type'] ?? '',
            ]);
            $response = OpenAI::embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $textToEmbed,
            ]);
            $validated['vector_embedding'] = json_encode($response->embeddings[0]->embedding);
        } catch (\Exception $e) {
            Log::error('Error generating embedding for JobVacancy: ' . $e->getMessage());
        }

        JobVacancy::create($validated);
        return redirect()->route('job-vacancies.index')->with('success', 'Job vacancy created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobVacancy = JobVacancy::with(['company' => function($q) { $q->withTrashed(); }, 'jobCategory'])->findOrFail($id);
        return view('job-vacancy.show', compact('jobVacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $companies = Company::all();
        $jobCategories = JobCategory::all();
        $jobTypes = JobType::cases();
        return view('job-vacancy.edit', compact('jobVacancy', 'companies', 'jobCategories', 'jobTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobVacancyUpdateRequest $request, string $id)
    {
        $validated = $request->validated();
        $jobVacancy = JobVacancy::findOrFail($id);
        
        try {
            $textToEmbed = json_encode([
                'title' => $validated['title'] ?? $jobVacancy->title,
                'description' => $validated['description'] ?? $jobVacancy->description,
                'location' => $validated['location'] ?? $jobVacancy->location,
                'type' => $validated['type'] ?? $jobVacancy->type,
            ]);
            $response = OpenAI::embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $textToEmbed,
            ]);
            $validated['vector_embedding'] = json_encode($response->embeddings[0]->embedding);
        } catch (\Exception $e) {
            Log::error('Error generating embedding for JobVacancy: ' . $e->getMessage());
        }

        $jobVacancy->update($validated);

        if($request->query('redirectToList') == 'false'){
            return redirect()->route('job-vacancies.show', $id)->with('success', 'Job vacancy updated successfully!');
        }

        return redirect()->route('job-vacancies.index')->with('success', 'Job vacancy updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $jobVacancy->delete();
        return redirect()->route('job-vacancies.index')->with('success', 'Job vacancy deleted successfully');
    }

    public function restore(string $id)
    {
        $jobVacancy = JobVacancy::withTrashed()->findOrFail($id);
        $jobVacancy->restore();
        return redirect()->route('job-vacancies.index', ['archived' => 'true'])->with('success', 'Job vacancy restored successfully');
    }
}