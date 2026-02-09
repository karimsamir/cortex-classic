<?php

declare(strict_types=1);

namespace Cortex\Universities\Models;

use Cortex\Universities\Models\University as BaseUniversity;

class University extends BaseUniversity
{
    // The model extends the base University model from the package
    // You can add any additional functionality or overrides here

    /**
     * Get validation rules for university fields.
     */
    public static function getValidationRules(): array
    {
        return [
            'name' => 'required|string|max:256',
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
    public static function getValidationMessages(): array
    {
        return [
            'name.required' => 'University name is required.',
            'country.required' => 'Country is required.',
            'website.url' => 'Please provide a valid website URL.',
            'email.email' => 'Please provide a valid email address.',
        ];
    }
}
