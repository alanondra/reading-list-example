<?php

namespace App\Exceptions;

use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NotImplementedException extends HttpException
{
	public function __construct(string $message = null, Throwable $previous = null, array $headers = [], ?int $code = 0)
	{
		$message ??= 'This feature is not presently implemented.';

		return parent::__construct(501, $message, $previous, $headers, $code);
	}
}
