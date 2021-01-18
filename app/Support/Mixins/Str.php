<?php

namespace App\Support\Mixins;

/**
 * @mixin \Illuminate\Support\Str
 */
class Str
{
	/**
	 * Set up the normalize macro.
	 *
	 * @return function
	 */
	public function normalize()
	{
		/**
		 * Normalize line endings of a string.
		 *
		 * @param  string|null  $value
		 * @param  string  $eol
		 *
		 * @return string|null
		 */
		return function (?string $value, string $eol = "\n") {
			if (empty($value)) {
				return $value;
			}

			return preg_replace('/\R/u', $eol, $value);
		};
	}
}
