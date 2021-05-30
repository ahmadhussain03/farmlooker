<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAnimalRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'animal_id' => 'required',
            'type' => 'required|string|max:255',
            'breed' => 'required|string|max:255',
            'add_as' => 'required|in:purchased,calved',
            'male_breeder_id' => 'nullable|string|max:255',
            'female_breeder_id' => 'nullable|string|max:255',
            'sex' => 'required|in:male,female',
            'dob' => 'required',
            'purchase_date' => 'nullable|date',
            'location' => 'required',
            'disease' => 'required|in:healthy,sick',
            'price' => 'nullable|float',
        ];
    }
}
