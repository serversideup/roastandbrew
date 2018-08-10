<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EditBrewMethodRequest extends FormRequest
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
         'method'       => 'required',
         'icon'       => 'required'
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
           'method'             => 'A name for the brew method is required.',
           'icon'             => 'An icon for the brew method is required.'
         ];
     }
}
