<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Author extends AbstractModel
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'authors';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'description',
	];

	/**
	 * Set up the withName scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $name
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithName(Builder $query, string $name)
	{
		return $query->where('name', $name);
	}

	/**
	 * Set up the books relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function books()
	{
		return $this->belongsToMany(Book::class, 'book_authors', 'author_id', 'book_id');
	}
}
