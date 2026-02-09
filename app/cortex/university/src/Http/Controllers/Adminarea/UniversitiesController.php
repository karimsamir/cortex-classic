<?php

declare(strict_types=1);

namespace App\Cortex\University\Http\Controllers\Adminarea;

use App\Cortex\University\Models\University;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UniversitiesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'universities.models.university';

    /**
     * Display a listing of resource.
     */
    public function index(Request $request): View
    {
        $universities = University::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('country', 'like', "%{$search}%")
                      ->orWhere('city', 'like', "%{$search}%");
            })
            ->when($request->country, function ($query, $country) {
                $query->where('country', $country);
            })
            ->when($request->funding, function ($query, $funding) {
                $query->where('funding', $funding);
            })
            ->orderBy('name')
            ->paginate(20);

        $countries = University::distinct()->pluck('country')->sort();
        $fundingTypes = University::distinct()->pluck('funding')->filter()->sort();

        return view('adminarea.universities.index', compact(
            'universities',
            'countries',
            'fundingTypes'
        ));
    }

    /**
     * Show form for creating a new resource.
     */
    public function create(): View
    {
        return view('adminarea.universities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->getValidationRules(), $this->getValidationMessages());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = $validator->validated();

        // Convert languages from comma-separated string to array
        if (isset($data['languages']) && is_string($data['languages'])) {
            $data['languages'] = array_map('trim', explode(',', $data['languages']));
            $data['languages'] = array_filter($data['languages']);
        }

        $university = University::create($data);

        return redirect()
            ->route('adminarea.universities.index')
            ->with('success', 'University created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(University $university): View
    {
        return view('adminarea.universities.show', compact('university'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(University $university): View
    {
        return view('adminarea.universities.edit', compact('university'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, University $university): RedirectResponse
    {
        $validator = Validator::make($request->all(), $this->getValidationRules($university->id), $this->getValidationMessages());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = $validator->validated();

        // Convert languages from comma-separated string to array
        if (isset($data['languages']) && is_string($data['languages'])) {
            $data['languages'] = array_map('trim', explode(',', $data['languages']));
            $data['languages'] = array_filter($data['languages']);
        }

        $university->update($data);

        return redirect()
            ->route('adminarea.universities.index')
            ->with('success', 'University updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(University $university): RedirectResponse
    {
        $university->delete();

        return redirect()
            ->route('adminarea.universities.index')
            ->with('success', 'University deleted successfully!');
    }

    /**
     * Get validation rules for university fields.
     */
    private function getValidationRules(?int $id = null): array
    {
        return [
            'name' => 'required|string|max:256|unique:universities,name,' . $id,
            'alt_name' => 'nullable|string|max:256',
            'country' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'telephone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'fax' => 'nullable|string|max:50',
            'funding' => 'nullable|string|max:255',
            'languages' => 'nullable|array',
            'academic_year' => 'nullable|string',
            'accrediting_agency' => 'nullable|string',
        ];
    }

    /**
     * Get custom validation messages.
     */
    private function getValidationMessages(): array
    {
        return [
            'name.required' => 'University name is required.',
            'name.unique' => 'University name already exists.',
            'country.required' => 'Country is required.',
            'website.url' => 'Please provide a valid website URL.',
            'email.email' => 'Please provide a valid email address.',
        ];
    }
}
