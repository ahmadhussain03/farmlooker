<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnimalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'animal_id' => 'nullable',
            'type' => 'nullable|string|max:255',
            'breed' => 'nullable|string|max:255',
            'add_as' => 'nullable|in:purchased,calved',
            'male_breeder_id' => 'nullable|string|max:255',
            'female_breeder_id' => 'nullable|string|max:255',
            'sex' => 'nullable|in:male,female',
            'dob' => 'nullable',
            'purchase_date' => 'nullable|date',
            'location' => 'nullable',
            'disease' => 'nullable|in:healthy,sick',
            'price' => 'nullable|float',
        ];
    }
}
