<?php

namespace App\Http\Requests\Author;

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
		return $this->getCommonRules();
	}
}
