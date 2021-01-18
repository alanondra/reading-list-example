<?php

namespace App\Support;

use Throwable;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Carbon;

class Parse
{
	public static function dateTime($value): ?Carbon
	{
		if (is_null($value)) {
			return null;
		}

		try {
			$dt = Carbon::parse($value);
		} catch (Throwable $exc) {
			//
		}

		return $dt ?: null;
	}

	public static function timezone($value): ?CarbonTimeZone
	{
		if (is_null($value)) {
			return null;
		}

		try {
			$tz = CarbonTimeZone::create($value);
		} catch (Throwable $exc) {
			//
		}

		return $tz ?: null;
	}
}
