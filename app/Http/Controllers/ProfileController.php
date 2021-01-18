<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use App\Http\Resources\User\Resource;

class ProfileController extends AbstractController
{
	public function update(Request $request)
	{
		$user = $request->user();

		$input = $request->only([
//			'login',
//			'email',
			'name',
		]);

		$password = $request->input('password');
		$newPassword = $request->input('password_new');

		$credentials = $request->only(['login', 'password']);

		try {
			if (!Auth::validate($credentials)) {
				throw new AuthenticationException(trans('auth.password'));
			}

			foreach ($input as $prop => $value) {
				$user->$prop = $value;
			}

			if (!empty($newPassword)) {
				$user->setPassword($newPassword);
			}

			$user->save();

			if ($request->expectsJson()) {
				return response()
					->json([
						'success' => trans('auth.profile'),
						'user' => new Resource($user),
					]);
			} else {
				$request->session()->flash('success', trans('auth.profile'));

				return back();
			}
		} catch (Throwable $exc) {
			if (!$request->expectsJson()) {
				$request->session()->flash('error');

				return back()
					->withInput($request->validated());
			}
		}
	}
}
