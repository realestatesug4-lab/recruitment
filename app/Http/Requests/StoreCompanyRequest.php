<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:companies,slug',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
        ];
    }
}
