<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Error! The priority field must be an integer..
        return [
            'name' => 'required|string|max:255',
            'client' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rate' => ['required'],
            'rate_type' => 'required',
            'priority' => 'required',
            'leader' => 'required|exists:employees,id',
            'team' => 'required|array',
            'team.*' => 'exists:employees,id',
            'description' => 'required|string',
            'project_files' => 'nullable|array',
            'project_files.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}
