<?php

namespace App\Http\Requests\ReadingList;

use App\Http\Requests\Concerns\RequireUser;

class StoreRequest extends AbstractFormRequest
{
	use RequireUser;

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return array_replace(
			$this->getCommonRules(),
			[
				'books' => 'array|min:1',
				'books.*' => 'exists:books,id',
			]
		);
	}
}
