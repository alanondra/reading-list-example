<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\AbstractFormRequest;
use App\Http\Requests\Concerns\RequireUser;

class UpdateRequest extends AbstractFormRequest
{
	use RequireUser;

	/**
	 * The key to be used for the view error bag.
	 *
	 * @var string
	 */
	protected $errorBag = 'profile';

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required|string|max:256',
			'password' => 'required|string|max:256',
			'password_new' => 'confirmed|string|max:256',
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return trans('auth.attributes');
	}
}
