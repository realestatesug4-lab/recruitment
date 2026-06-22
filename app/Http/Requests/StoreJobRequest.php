<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'experience_level' => 'required|string',
            'location' => 'nullable|string',
            'salary_min' => 'nullable|integer',
            'salary_max' => 'nullable|integer',
            'deadline' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:job_categories,id',
            'skills' => 'nullable|array',
            'skills.*' => 'integer|exists:skills,id',
        ];
    }
}
