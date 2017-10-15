<?php

namespace App\Http\Requests\Staff\Mangement\AppSetting;

use App\Http\Requests\Request;

class StoreGroupType extends Request
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
            'type' => 'required|unique:social_mongodb.iGroupType',
            'name' => 'required',
            'icon' => 'required|image',
            'categories_refer' => 'array'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Hãy nhập loại nhóm',
            'type.unique' => 'Loại nhóm đã tồn tại',
            'name.required' => 'Hãy nhập tên loại nhóm',
            'icon.required' => 'Hãy nhập ảnh loại nhóm',
            'icon.image' => 'Ảnh không hợp lệ',
            'icon.url' => 'Ảnh loại nhóm không hợp lệ'
        ];
    }
}
