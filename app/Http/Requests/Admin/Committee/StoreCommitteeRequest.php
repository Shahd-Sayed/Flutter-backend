<?php

namespace App\Http\Requests\Admin\Committee;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommitteeRequest extends FormRequest {
    /**
    * Determine if the user is authorized to make this request.
    */

    public function authorize(): bool {
        return !auth()->user()?->can( 'committee.create' );
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
            'image' => [ 'nullable', 'image', 'max:10240' ],
            'courses' => [ 'nullable', 'array' ],
            'courses.*.course_name' => [ 'required', 'string', 'max:255' ],
            'courses.*.course_description' => [ 'nullable', 'string', 'max:20000000' ],
            'courses.*.link' => [ 'nullable', 'string', 'url', 'max:255' ],
            // 'courses.*.image' => [ 'nullable', 'image', 'max:10240' ],
            'courses.*.date' => [ 'required', 'date' ],
            'about_us' => [ 'nullable', 'string', 'max:1000' ],
        ];

    }
}
