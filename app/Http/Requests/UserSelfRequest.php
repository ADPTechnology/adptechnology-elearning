<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSelfRequest extends FormRequest
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
            "name" => ['required', 'max:255'],
            "paternal" => ['required', 'max:255'],
            "maternal" => ['required', 'max:255'],
            "email" => ['required', 'max:255', 'email', 'unique:users'],
        ];
    }
}
