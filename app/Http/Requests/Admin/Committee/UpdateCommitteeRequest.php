<?php

namespace App\Http\Requests\Admin\Committee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommitteeRequest extends FormRequest {
    /**
    * Determine if the user is authorized to make this request.
    */

    public function authorize(): bool {
        return !auth()->user()?->can( 'committee.update' );
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */

    public function rules(): array {
        return [
            'name' => [ 'required', 'string', 'max:255' ],
            'description' => [ 'nullable', 'string', 'max:1000' ],
            'courses' => [ 'nullable', 'array' ],
            'courses.*.course_name' => [ 'required', 'string', 'max:255' ],
            'courses.*.course_description' => [ 'nullable', 'string', 'max:1000' ],
            'courses.*.link' => [ 'nullable', 'string', 'url', 'max:255' ],
            'about_us' => [ 'nullable', 'string', 'max:1000' ],
        ];
    }
}
