<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\AbstractFormRequest;
use App\Http\Requests\Concerns\AllowAny;

class RegisterRequest extends AbstractFormRequest
{
	use AllowAny;

	/**
	 * The key to be used for the view error bag.
	 *
	 * @var string
	 */
	protected $errorBag = 'auth';

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email' => 'required|email:filter|max:256|unique:users,email',
			'login' => 'required|string|max:256|unique:users,login',
			'password' => 'required|confirmed|string|max:256',
			'name' => 'required|string|max:256',
			'accept_tos' => 'required|accepted',
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
