<?php

namespace App\Http\Requests\ReadingList;

use App\Http\Requests\Concerns\RequireUser;

class UpdateRequest extends AbstractFormRequest
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
