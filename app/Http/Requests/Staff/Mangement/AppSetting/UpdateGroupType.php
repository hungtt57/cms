<?php

namespace App\Http\Requests\Staff\Mangement\AppSetting;

use App\Http\Requests\Request;

class UpdateGroupType extends Request
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
            'type' => '',
            'name' => '',
            'icon' => 'image',
            'categories_refer' => 'array'
        ];
    }
}
