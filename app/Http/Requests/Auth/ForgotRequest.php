<?php

namespace App\Http\Requests\Auth;

use Throwable;
use Illuminate\Support\Facades\{
	Auth,
	Event,
	Password,
};
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;

use App\Http\Requests\AbstractFormRequest;
use App\Http\Requests\Concerns\RequireGuest;

class ForgotRequest extends AbstractFormRequest
{
	use RequireGuest;

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
		];
	}

	/**
	 * Fulfill the request.
	 *
	 * @return boolean
	 *
	 * @throws \Illuminate\Auth\AuthenticationException
	 */
	public function fulfill()
	{
		$email = $this->only('email');

		$status = Password::sendResetLink($email);

		if ($status !== Password::RESET_LINK_SENT) {
			throw new AuthenticationException(trans($status));
		}

		return true;
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
