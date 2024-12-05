<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSettingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'setting_id' => ['required', 'exists:user_settings,id'],
            'setting_value' => ['required', 'string', 'max:255'],
            'method' => ['required', 'string','in:sms,email'],
        ];
    }
}
