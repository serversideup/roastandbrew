<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EditCafeRequest extends FormRequest
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
          'address'                 => 'required',
          'city'                    => 'required',
          'state'                   => 'required',
          'zip'                     => 'required'
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

          'address'                           => [ 'required' => 'The location needs to have an address.' ],
          'city'                              => [ 'required' => 'The location needs to have a city.' ],
          'state'                             => [ 'required' => 'The location needs to have a state.' ],
          'zip'                               => [
                                                    'required' => 'The location needs to have a zip.'
                                                 ]
        ];
    }
}
