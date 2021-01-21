<?php

namespace App\Http\Requests\ReadingList;

use App\Http\Requests\AbstractFormRequest as FormRequest;

abstract class AbstractFormRequest extends FormRequest
{
	/**
	 * The key to be used for the view error bag.
	 *
	 * @var string
	 */
	protected $errorBag = 'readingList';

	/**
	 * Get the common rules for editing Books.
	 *
	 * @return array
	 */
	protected function getCommonRules(): array
	{
		return [
			'name' => 'required|string|min:3|max:255',
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return trans('readingLists.attributes');
	}
}
