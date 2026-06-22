<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSeekerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'headline' => 'nullable|string|max:120',
            'bio' => 'nullable|string|max:1200',
            'location' => 'nullable|string|max:150',
            'experience_level' => 'nullable|string|max:80',
            'skills' => 'nullable|array',
            'skills.*' => 'required|string|max:80',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];
    }
}
