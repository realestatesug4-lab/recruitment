<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array {
        return [
            'status' => 'required|string|in:submitted,reviewing,shortlisted,rejected,scheduled,hired',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
