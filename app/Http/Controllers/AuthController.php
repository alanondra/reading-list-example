<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Facades\{
	Auth,
	Event,
	Hash,
	Password,
};
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Http\Requests\Auth\{
	RegisterRequest,
	ForgotRequest
};

class AuthController extends AbstractController
{
	public function register(RegisterRequest $request)
	{
		try {
			$user = User::create([
				'login' => $request->input('login'),
				'email' => $request->input('email'),
				'password' => Hash::make($request->input('password')),
				'name' => $request->input('name'),
			]);

			Auth::login($user);
	
			Event::dispatch(new Registered($user));
	
			if ($request->expectsJson()) {
				return response()
					->json([
						'success' => trans('auth.registered'),
					])
					->setStatusCode(200);
			}
	
			return redirect()
				->route('home');
		} catch (Throwable $exc) {
			if ($request->expectsJson()) {
				throw $exc;
			}

			return back()
				->withInput($request->validated())
				->withErrors([
					'email' => trans(Password::RESET_LINK_SENT)
				]);
		}
	}

	public function verify(EmailVerificationRequest $request)
	{
		try {
			$request->fulfill();

			$request->session()
				->put('success', trans('auth.verified'));

			return redirect()
				->route('login');
		} catch (Throwable $exc) {
			dd($exc);

			if ($request->expectsJson()) {
				throw $exc;
			}

			return back()
				->withInput($request->validated())
				->withErrors([
					'email' => trans(Password::RESET_LINK_SENT)
				]);
		}
	}

	public function resend(Request $request)
	{
		try {
			$request->user()
				->sendEmailVerificationNotification();

			if ($request->expectsJson()) {
				return response()
					->json([

					]);
			}

			return back()
				->with('message', 'Verification link sent!');
		} catch (Throwable $exc) {
			if ($request->expectsJson()) {
				throw $exc;
			}

			return back()
				->withInput($request->validated())
				->withErrors([
					'email' => trans(Password::RESET_LINK_SENT)
				]);
		}
	}

	public function forgot(ForgotRequest $request)
	{
		try {
			$request->fulfill();

			if ($request->expectsJson()) {
				return response(null, 200)
					->json([
						'messages' => [
							trans(Password::RESET_LINK_SENT)
						],
					]);
			}

			return back()
				->with('status', trans(Password::RESET_LINK_SENT));
		} catch (Throwable $exc) {
			if ($request->expectsJson()) {
				throw $exc;
			}

			return back()
				->withInput($request->validated())
				->withErrors([
					'email' => trans(Password::RESET_LINK_SENT)
				]);
		}
	}
}
