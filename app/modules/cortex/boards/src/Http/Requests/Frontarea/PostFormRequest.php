<?php

declare(strict_types=1);

namespace Cortex\Boards\Http\Requests\Frontarea;

use Cortex\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authenticated users can create posts; admin & owner logic handled in controllers/policies
        return true;
    }

    public function rules(): array
    {
        return [
            'board_id' => 'nullable|exists:cortex_boards,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
