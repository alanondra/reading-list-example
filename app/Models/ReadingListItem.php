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
	 * Set up the book relation.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function book()
	{
		return $this->hasOne(Book::class);
	}
}
