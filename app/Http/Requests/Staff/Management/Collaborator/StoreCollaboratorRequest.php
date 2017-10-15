<?php

namespace App\Http\Requests\Staff\Management\Collaborator;

use App\Http\Requests\Request;

class StoreCollaboratorRequest extends Request
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
            'name' => 'required|max:255',
            'avatar' => 'image',
            'address' => 'required',
            'email' => 'required|email|unique:collaborators',
            'phone_number' => 'required|unique:collaborators',
            'password' => 'min:6|confirmed',
        ];
    }
}