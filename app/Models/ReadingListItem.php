<?php

namespace App\Models;

class ReadingListItem extends AbstractModel
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'reading_list_items';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'reading_list_id',
		'book_id',
	];

	/**
	 * Set up the book relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function book()
	{
		return $this->belongsTo(Book::class);
	}

	/**
	 * Set up the readingList relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function readingList()
	{
		return $this->belongsTo(ReadingList::class);
	}
}
