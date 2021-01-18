<?php

namespace App\Exceptions;

use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Register the exception handling callbacks for the application.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->reportable(function (Throwable $e) {
			//
		});
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Throwable  $e
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \Throwable
	 */
	public function render($request, Throwable $e)
	{
		if ($request->wantsJson()) {
			return $this->renderApiException($request, $e);
		}

		return parent::render($request, $e);
	}

	/**
	 * Render an Exception under a common JSON format.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Throwable  $e
	 * @return \Illuminate\Http\Response
	 */
	protected function renderApiException($request, Throwable $e)
	{
		$e = $this->prepareException($this->mapException($e));

		$status = $this->getExceptionStatus($e);

		$body = [
			'errors' => [],
		];

		$error = [
			'code' => $e->getCode() ?: $status ?: 0,
			'message' => $this->getExceptionMessage($e),
		];

		$status ??= 500;

		if ($this->debug() && !($e instanceof ValidationException)) {
			$error = array_replace($error, [
				'class' => get_class($e),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTrace(),
			]);
		}

		if ($e instanceof ValidationException) {
			$error['errors'] = $e->errors();
		} elseif ($e instanceof QueryException) {
			if (!$this->debug()) {
				$error['message'] = 'Database error.';
			}
		}

		array_unshift($body['errors'], $error);

		return response()
			->json($body)
			->setStatusCode($status);
	}

	protected function getExceptionStatus($e)
	{
		if ($e instanceof HttpResponseException) {
			return $e->getResponse()->getStatusCode();
		}
		if ($e instanceof NotFoundHttpException) {
			return 404;
		}
		if ($e instanceof AuthenticationException) {
			return 401;
		}
		if ($e instanceof ValidationException) {
			return 422;
		}

		return null;
	}

	protected function getExceptionMessage(Throwable $e)
	{
		$allow = [
			HttpResponseException::class,
			NotFoundHttpException::class,
			AuthenticationException::class,
			ValidationException::class,
		];

		$type = get_class($e);

		if ($this->debug() || in_array($type, $allow)) {
			return $e->getMessage();
		}

		return trans('app.messages.error');
	}

	protected function debug(): bool
	{
		return config('app.debug');
	}
}
