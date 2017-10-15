<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreEventGiftRequest extends Request
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
            'type' => 'required|in:'.implode(',', array_keys(config('event.gift.type'))),
            'event' => 'required|exists:mongodb_event.events,_id',
            'image' => 'required|image'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Hãy nhập tên của món quà',
        ];
    }
}
