<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array {
        $companyId = $this->route('company')?->id;

        return [
            'name' => 'required|string|max:255',
            'slug' => ['nullable','string','max:255', Rule::unique('companies','slug')->ignore($companyId)],
            'website' => 'nullable|url',
            'description' => 'nullable|string',
        ];
    }
}
