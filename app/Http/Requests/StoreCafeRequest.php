<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCafeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name'    => 'required',
            'address' => 'required',
            'city'    => 'required',
            'state'   => 'required',
            'zip'     => 'required|regex:/\b\d{5}\b/'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
          'name.required'     => 'A name for the cafe is required.',
          'address.required'  => 'An address is required to add this cafe.',
          'city.required'     => 'A city is required to add this cafe.',
          'state.required'    => 'A state is required to add this cafe.',
          'zip.required'      => 'A zip code is required to add this cafe.',
          'zip.regex'         => 'The zip code entered is invalid.'
        ];
    }
}
