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
            'name'                    => 'required',
            'location.*.address'      => 'required',
            'location.*.city'         => 'required',
            'location.*.state'        => 'required',
            'location.*.zip'          => 'required|regex:/\b\d{5}\b/',
            'location.*.brew_methods' => 'sometimes|array',
            'website'                 => 'sometimes|url'
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
          'name.required'             => 'A name for the cafe is required.',
          'location.*.address'        => [ 'required' => 'Each location needs to have an address.' ],
          'location.*.city'           => [ 'required' => 'Each location needs to have a city.' ],
          'location.*.state'          => [ 'required' => 'Each location needs to have a state.' ],
          'location.*.zip'            => [
                                            'required' => 'Each location needs to have a zip.',
                                            'regex'    => 'The zip code for your location is invalid.'
                                         ],
          'location.*.brew_methods'   => [ 'array' => 'The brew methods must be an array of ids' ],
          'website.url'               => 'The website must be a proper URL.'
        ];
    }
}
