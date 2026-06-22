<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSeekerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'headline' => 'sometimes|string|max:120',
            'bio' => 'sometimes|nullable|string|max:1200',
            'location' => 'sometimes|nullable|string|max:150',
            'experience_level' => 'sometimes|nullable|string|max:80',
            'skills' => 'sometimes|nullable|array',
            'skills.*' => 'required_with:skills|string|max:80',
            'resume' => 'sometimes|nullable|file|mimes:pdf,doc,docx|max:10240',
        ];
    }
}
