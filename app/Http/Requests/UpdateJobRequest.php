<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'type' => 'sometimes|string',
            'experience_level' => 'sometimes|string',
            'location' => 'nullable|string',
            'salary_min' => 'nullable|integer',
            'salary_max' => 'nullable|integer',
            'deadline' => 'nullable|date',
            'categories' => 'nullable|array',
            'skills' => 'nullable|array',
        ];
    }
}
