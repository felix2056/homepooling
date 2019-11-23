<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileURequest extends SanitizeRequest
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
        $this->sanitize();
        return [
            'name'=>'required|string|max:255',
            'family_name'=>'required|string|max:255',
            'email'=>'required|email',
            'phone'=>['regex:/^(\+|00)(?:[0-9] ?){6,14}[0-9]$/','nullable'],
            'gender'=>'string|max:20|nullable',
            'profession'=>'string|max:255|nullable',
            'origin'=>'string|max:255|nullable',
            'description'=>'string|max:2048|nullable',
            'budget'=>'string|max:255',
            'location'=>'string|max:2048|nullable',
            'property_type'=>'string|max:255|nullable',
            'has_bathroom'=>'integer|max:20',
            'p_empty'=>'integer|max:20',
            'single'=>'integer|max:20',
            'epc'=>'string|max:20|nullable',
            'amenities'=>'array|nullable',
            'photo'=>'image|max:4000|nullable'
        ];
    }
}
