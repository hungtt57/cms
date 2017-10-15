<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreEventRequest extends Request
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
            'name' => 'required',
            'description' => '',
            'startTime' => 'required|date|after:now',
            'endTime' => 'required|date|after:startTime',
            'image' => 'required|image',
            'giftExchange' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Hãy nhập tên sự kiện',
            'startTime.required' => 'Hãy chọn thời gian bắt đầu sự kiện',
            'startTime.after' => 'Thời gian bắt đầu không thể trước thời điểm hiện tại',
            'endTime.required' => 'Hãy chọn thời gian kết thúc sự kiện',
            'endTime.after' => 'Thời gian kết thúc sự kiện không thể trước thời gian ban đầu',
            'image.required' => 'Hãy chọn ảnh cho sự kiện này',
            'image.image' => 'Hãy chọn ảnh cho sự kiện này'
        ];
    }
}
