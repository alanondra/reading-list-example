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
	 * Set up the user relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
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
