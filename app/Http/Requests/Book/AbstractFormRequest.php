<?php

namespace App\Http\Requests\Book;

use App\Http\Requests\AbstractFormRequest as FormRequest;

abstract class AbstractFormRequest extends FormRequest
{
	/**
	 * The key to be used for the view error bag.
	 *
	 * @var string
	 */
	protected $errorBag = 'book';

	/**
	 * Get the common rules for editing Books.
	 *
	 * @return array
	 */
	protected function getCommonRules(): array
	{
		return [
			'isbn' => 'required|string|min:10|max:13',
			'name' => 'required|string|min:3|max:255|unique:books,name',
			'description' => 'nullable|string|max:65535',
			'authors' => 'array|min:1',
			'authors.*' => 'exists:authors,id',
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return trans('author.attributes');
	}
}
