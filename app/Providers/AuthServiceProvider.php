<?php

namespace App\Providers;

use App\Mail\Auth\VerifyEmail as AuthVerifyEmail;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		VerifyEmail::createUrlUsing(function ($notifiable) {
			return URL::temporarySignedRoute(
				'auth.verify',
				Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
				[
					'id' => $notifiable->getKey(),
					'hash' => sha1($notifiable->getEmailForVerification()),
				]
			);
		});
	}
}
