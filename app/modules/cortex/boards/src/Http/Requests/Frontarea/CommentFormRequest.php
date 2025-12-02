<?php

declare(strict_types=1);

namespace Cortex\Boards\Http\Requests\Frontarea;

use Cortex\Foundation\Http\FormRequest;

class CommentFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Let Controller / Policies handle permission, allow validation
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => 'required|string',
            'parent_id' => 'nullable|exists:cortex_boards_comments,id',
        ];
    }
}
