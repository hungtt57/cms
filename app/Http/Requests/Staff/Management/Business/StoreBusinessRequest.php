<?php

namespace App\Http\Requests\Staff\Management\Business;

use App\Http\Requests\Request;
use App\Models\Enterprise\GLN;

class StoreBusinessRequest extends Request
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
            'logo' => 'image',
            'gln' => 'unique:gln,gln,NULL,id,status,' . GLN::STATUS_APPROVED,
            'country_id' => 'required|exists:icheck_product.country,id',
            'address' => 'required',
            'email' => 'required|email',
            'login_email' => 'required|email|unique:businesses,login_email',
            'password' => 'min:6|confirmed',
        ];
    }
}
