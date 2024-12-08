<?php

namespace App\Http\Requests\Admin\Member;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth()->user()?->can('member.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required' , 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:200000000'],
            'image' => ['nullable', 'image', 'max:10240'],
            'link1' => ['nullable' , 'string', 'max:255','url'],
            'link2' => ['nullable', 'string', 'max:255','url'],
            'link3' => ['nullable' , 'string', 'max:255','url'],
        ];
    }
}
