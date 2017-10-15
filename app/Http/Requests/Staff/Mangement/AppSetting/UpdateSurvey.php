<?php

namespace App\Http\Requests\Staff\Mangement\AppSetting;

use App\Http\Requests\Request;

class UpdateSurvey extends Request
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
            'image' => 'image',
            'message' => '',
            'link' => 'url',
            'location' => ''
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'Ảnh survey không hợp lệ',
            'link.url' => 'Liên kết với survey không hợp lệ'
        ];
    }
}
