<?php

namespace App\Http\Requests\Session;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
	Auth,
	Event,
	RateLimiter,
};
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\AbstractFormRequest;
use App\Http\Requests\Concerns\AllowAny;

class StoreRequest extends AbstractFormRequest
{
	use AllowAny;

	/**
	 * The key to be used for the view error bag.
	 *
	 * @var string
	 */
	protected $errorBag = 'session';

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'login' => 'required|string|max:256',
			'password' => 'required|string|max:256',
			'remember' => 'boolean',
		];
	}

	/**
	 * Attempt to authenticate the request's credentials.
	 *
	 * @return void
	 *
	 * @throws \Illuminate\Validation\AuthenticationException
	 */
	public function authenticate()
	{
		$this->ensureIsNotRateLimited();

		$credentials = $this->only(['login', 'password']);

		if (!Auth::attempt($credentials, $this->filled('remember'))) {
			RateLimiter::hit($this->throttleKey());

			throw new AuthenticationException(trans('auth.failed'));
		}

		RateLimiter::clear($this->throttleKey());
	}

	/**
	 * Ensure the login request is not rate limited.
	 *
	 * @return void
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function ensureIsNotRateLimited()
	{
		if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
			return;
		}

		Event::dispatch(new Lockout($this));

		$seconds = RateLimiter::availableIn($this->throttleKey());

		throw ValidationException::withMessages([
			'login' => trans('auth.throttle', [
				'seconds' => $seconds,
				'minutes' => ceil($seconds / 60),
			]),
		]);
	}

	/**
	 * Get the rate limiting throttle key for the request.
	 *
	 * @return string
	 */
	public function throttleKey()
	{
		return sprintf('%s|%s', Str::lower($this->input('login')), $this->ip());
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
