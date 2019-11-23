<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SanitizeRequest extends FormRequest
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
            //
        ];
    }
    protected function sanitize(){
        $inputs=array();
        foreach($this->all() as $name => $input){
		if (gettype($input) == 'string' && !($name=='birthday'&&$input==0) && !($name=='avail_from'&&$input==0)) {
			$inputs[$name] = filter_var($input, FILTER_SANITIZE_STRING);
			
		}elseif(gettype($input) == 'array'){
			$inputs[$name] = $input;
		}
        }
        $this->replace($inputs);
        
        return true;
    }
}
