<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\Mixins;

class SupportServiceProvider extends ServiceProvider
{
	protected static array $mixins = [
		\Illuminate\Support\Str::class => [
			Mixins\Str::class,
		],
		\Illuminate\Database\Schema\Blueprint::class => [
			Mixins\Database\Schema\Blueprint::class,
		],
	];

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		foreach (static::$mixins as $support => $mixins) {
			foreach ($mixins as $mixin) {
				$support::mixin(new $mixin());
			}
		}
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}
}
