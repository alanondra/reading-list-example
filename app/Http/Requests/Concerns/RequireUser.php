<?php

namespace App\Http\Requests\Concerns;

trait RequireUser
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return !!$this->user();
	}
}
