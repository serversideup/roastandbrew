<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EditCompanyRequest extends FormRequest
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
         'name'       => 'required',
         'website'    => 'required|url'
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
           'name.required'     => 'A name for the company is required.',
           'website.url'       => 'The website must be a proper URL.'
         ];
     }
}
