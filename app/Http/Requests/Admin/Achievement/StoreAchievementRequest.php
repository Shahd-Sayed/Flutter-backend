<?php

namespace App\Http\Requests\Admin\Achievement;

use Illuminate\Foundation\Http\FormRequest;

class StoreAchievementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth()->user()?->can('achievement.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'], 
            'location' => ['nullable', 'string', 'max:255'], 
            'members' => ['nullable', 'integer', 'min:1'],
            'rank' => ['required', 'string', 'max:100'], 
            'image' => ['nullable', 'image', 'max:10240'],

        ];
    }
}
