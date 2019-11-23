<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertiesFSURequest extends SanitizeRequest
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
        $rules=[
            'address'=>'required|string|max:1024',
            'address_long'=>'string|max:2048',
            'town'=>'string|max:1024',
            'postal_code'=>'string|max:255',
            'lat'=>'string|max:255',
            'long'=>'string|max:255',
            'property_type'=>'required|string|max:255',
            'user_type'=>'required|string|max:255',
            'epc'=>'string|max:20|nullable',
            'rooms_no'=>'integer|max:100',
            'minimum_stay'=>'integer|max:255|nullable',
            'description'=>'string|max:2048|nullable',
            'room_price_main'=>'integer|max:9999|nullable',
            'room_deposit_main'=>'integer|max:9999|nullable',
            'room_bills_main'=>'integer|max:9999|nullable',
            'amenities'=>'array|nullable',
            'acceptings'=>'array|nullable',
            'contract'=>'mimes:pdf|max:4000|nullable',
            'epcert'=>'mimes:pdf|max:4000|nullable',
        ];
	if($this->hasFile('photo')){
            for($i=0;$i<count($this->file('photo'));$i++){
                $rules['photo.'.$i]='required|image|max:4000';
            }
        }elseif($this->has('photo_tbd')){
		$rules['photo_tbd']='array';
        }else{
		if($this->method()!='PUT'){
			$rules['image']='required|image|max:4000';
		}
        }
        
        return $rules;
    }
}
