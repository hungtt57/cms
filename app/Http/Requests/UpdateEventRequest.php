<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateEventRequest extends Request
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
            'name' => '',
            'description' => '',
            'startTime' => 'date',
            'endTime' => 'date|after:startTime',
            'image' => 'image',
            'giftExchange' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'startTime.after' => 'Thời gian bắt đầu không thể trước thời điểm hiện tại',
            'endTime.after' => 'Thời gian kết thúc sự kiện không thể trước thời gian ban đầu',
            'image.image' => 'Hãy chọn ảnh cho sự kiện này'
        ];
    }
}
