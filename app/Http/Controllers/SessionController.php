<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\Resource;
use App\Http\Requests\Session\StoreRequest;

class SessionController extends AbstractController
{
	public function index(Request $request)
	{
		if (!$request->expectsJson()) {
			abort(404);
		}

		$session = $request->getSession();

		$user = $request->user();

		$data = [
			'user' => (!empty($user) ? new Resource($user) : null),
			'message' => $session->pull('message', null),
			'success' => $session->pull('success', null),
			'warning' => $session->pull('warning', null),
			'error' => $session->pull('error', null),
		];

		return response()
			->json($data);
	}

	public function store(StoreRequest $request)
	{
		try {
			$request->authenticate();

			$request->session()->regenerate();
	
			$user = $request->user();

			if ($request->expectsJson()) {
				return response()
					->json([
						'user' => new Resource($user),
					]);
			}

			return redirect()
				->intended(route('home'));
		} catch (Throwable $exc) {
			if ($request->expectsJson()) {
				throw $exc;
			}
	
			return back()
				->withErrors([
					'login' => trans('auth.failed'),
				]);
		}
	}

	public function destroy(Request $request)
	{
		Auth::logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		if ($request->expectsJson()) {
			return response()
				->json(true);
		}

		return redirect()
			->route('home');
	}
}
