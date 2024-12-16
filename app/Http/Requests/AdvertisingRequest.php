<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisingRequest extends FormRequest
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
            "text" => ['required'],
            "plan" => ['required'],
            "active" => ['nullable'],
            "amount" => ['required'],
            "amount_type" => ['required'],
            "expired_date" => ['required'],
            "code" => ['nullable', "max:16", "min:4"],
            "courses" => ['nullable', 'array'],
            "especify_courses" => ['nullable'],
            "especify_users" => ['nullable'],
            "users" => ['nullable', 'array'],
            "generation_type" => ['required'],
            "type" => ['required'],
            "flg_used" => ['nullable']
        ];
    }
}
