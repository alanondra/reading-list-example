<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Book extends AbstractModel
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'books';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'isbn',
		'name',
		'description',
	];

	/**
	 * Set up the withIsbn scope.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $isbn
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithIsbn(Builder $query, string $isbn)
	{
		return $query->where('isbn', $isbn);
	}

	/**
	 * Set up the authors relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function authors()
	{
		return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
	}

	/**
	 * Set up the readingListItems relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function readingListItems()
	{
		return $this->hasMany(ReadingListItem::class);
	}

	/**
	 * Set up the readingLists relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function readingLists()
	{
		return $this->hasManyThrough(ReadingList::class, ReadingListItem::class);
	}

	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
		return 'isbn';
	}
}
