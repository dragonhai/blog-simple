<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Auth;

class PostFormRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if($this->user()->canPost())
		{
			return true;
		}
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'title' => ['required', 'unique:posts', 'max:255', 'Regex:/^[\p{L}0-9 ]+$/u'],
			'body' => 'required',
			'published_at' => 'required',
		];
	}	
}
