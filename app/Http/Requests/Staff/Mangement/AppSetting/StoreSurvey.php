<?php

namespace App\Http\Requests\Staff\Mangement\AppSetting;

use App\Http\Requests\Request;

class StoreSurvey extends Request
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
            'image' => 'required|image',
            'message' => 'required',
            'link' => 'required|url',
            'location' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Hãy nhập ảnh của survey',
            'image.image' => 'Ảnh survey không hợp lệ',
            'message.required' => 'Hãy nhập thông điệp của survey',
            'link.required' => 'Hãy nhập liên kết với survey',
            'link.url' => 'Liên kết với survey không hợp lệ',
            'location.required' => 'Hãy nhập vị trí nhận survey',
            'location.url' => 'Vị trí survey không hợp lệ'
        ];
    }
}
