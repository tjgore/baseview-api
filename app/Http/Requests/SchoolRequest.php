<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'phone' => 'string|required',
            'website' => 'url|nullable',
            'principal' => 'string|required',
            'vice_principal' => 'string|nullable',
            'about' => 'string|nullable',
            'slogan' => 'string|nullable'
        ];
    }
}
