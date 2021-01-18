<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractFormRequest extends FormRequest
{
	/**
	 * Create the default validator instance.
	 *
	 * @param  \Illuminate\Contracts\Validation\Factory  $factory
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function createDefaultValidator(Factory $factory)
	{
		$validator = parent::createDefaultValidator($factory);

		$afterMethod = 'after';

		if (method_exists($this, $afterMethod)) {
			$validator->after([$this, $afterMethod]);
		}

		return $validator;
	}
}
