<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddCityRequest extends FormRequest
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
         'name'         => 'required',
         'state'        => 'required',
         'country'      => 'required',
         'radius'       => 'required',
         'latitude'     => 'required',
         'longitude'    => 'required'
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
           'name'         => 'A name is required for a city.',
           'state'        => 'A state is required for the city',
           'country'      => 'A country is required for the city',
           'radius'       => 'A radius in miles is required for the city',
           'latitude'     => 'Latitude is required for the city.',
           'longitude'    => 'Longitude is required for the city.'
         ];
     }
}
