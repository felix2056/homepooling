<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WantedsFSURequest extends SanitizeRequest
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
            'location'=>'required|string|max:1024',
            'lat'=>'required|string|max:255',
            'long'=>'required|string|max:255',
            'range'=>'string|max:100|nullable',
            'price'=>'string|max:100|nullable',
            'type'=>'string|max:255|nullable',
            'epc'=>'string|max:20|nullable',
            'people'=>'integer|nullable',
            'rooms'=>'integer|nullable',
            'has_bathroom'=>'integer|max:1',
            'p_empty'=>'integer|max:1',
            'single'=>'integer|max:1',
            'amenities'=>'array|nullable',
            'acceptings'=>'array|nullable',
        ];
    }
}
