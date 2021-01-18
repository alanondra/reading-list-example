<?php

namespace App\Http\Requests\Author;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\AbstractFormRequest as FormRequest;

abstract class AbstractFormRequest extends FormRequest
{
	/**
	 * The key to be used for the view error bag.
	 *
	 * @var string
	 */
	protected $errorBag = 'author';

	/**
	 * Get the common rules for editing Authors.
	 *
	 * @return array
	 */
	protected function getCommonRules(): array
	{
		return [
			'name' => 'required|string|min:3|max:255|unique:authors,name',
			'description' => 'nullable|string|max:65535',
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return trans('authors.attributes');
	}

	/**
	 * Run after validation.
	 *
	 * @param  \Illuminate\Contracts\Validation\Validator  $validator
	 *
	 * @return void
	 */
	public function after(Validator $validator)
	{
		$input = $this->input();

		if (is_string($input['description'] ?? null)) {
			$input['description'] = Str::normalize($input['description']);
		}

		$this->replace($input);
	}
}
