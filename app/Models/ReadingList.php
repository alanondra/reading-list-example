<?php

namespace App\Models;

class ReadingList extends AbstractModel
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'reading_lists';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
	];

	/**
	 * Set up the user relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Set up the items relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function items()
	{
		return $this->hasMany(ReadingListItem::class);
	}

	/**
	 * Set up the books relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function books()
	{
		return $this->hasManyThrough(Book::class, ReadingListItem::class);
	}
}
