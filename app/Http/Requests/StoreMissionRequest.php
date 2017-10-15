<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreMissionRequest extends Request
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
            'hook' => 'required|regex:/^[a-zA-Z0-9_]*$/',
            'point' => 'numeric',
            'maxPerDay' => 'numeric',
            'repeat' => 'numeric',
            'maxComplete' => 'numeric',
            'param' => 'array',
            'operator' => 'array',
            'value' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'hook.required' => 'Hãy nhập hook cho nhiệm vụ',
            'hook.regex' => 'Tên hook không hợp lệ (chữ cái, chữ số hoặc dấu gạch dưới)',
        ];
    }
}
