<?php

declare(strict_types=1);

namespace Cortex\Boards\Http\Requests\Adminarea;

use Cortex\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
{
    public function authorize(): bool
    {
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
