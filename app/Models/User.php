<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasFactory, Notifiable;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'email',
		'login',
		'password',
		'name',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
		'two_factor_secret',
		'two_factor_recovery_codes',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	/**
	 * Set up the readingLists relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function readingLists()
	{
		return $this->hasMany(ReadingList::class);
	}

	/**
	 * Set up the withLogin scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $login
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithLogin(Builder $query, string $login): Builder
	{
		return $query->where('login', $login);
	}

	/**
	 * Set up the withLogin scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $login
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithEmail(Builder $query, string $email): Builder
	{
		return $query->where('email', $email);
	}

	/**
	 * Set up the withName scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $name
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithName(Builder $query, string $name): Builder
	{
		return $query->where('name', $name);
	}

	/**
	 * Set up the whereVerified scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  bool  $verified
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function whereVerified(Builder $query, bool $verified = true): Builder
	{
		return (($verified)
			? $query->whereNotNull('email_verified_at')
			: $query->whereNull('email_verified_at')
		);
	}

	/**
	 * Update the user's password.
	 *
	 * @param  string  $password
	 *
	 * @return $this
	 */
	public function setPassword(string $password)
	{
		$this->password = Hash::make($password);

		return $this;
	}

	/**
	 * Get the email address that should be used for verification.
	 *
	 * @return string
	 */
	public function getEmailForVerification()
	{
		return $this->email;
	}
}
