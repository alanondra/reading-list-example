<?php

namespace App\Http\Resources\Author;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Book\Collection as BookCollection;

class Resource extends JsonResource
{
	protected bool $loadBooks = false;

	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		$array = [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
		];

		if ($this->loadBooks) {
			$this->resource->loadMissing(['books']);

			$books = (new BookCollection($this->resource->books))->toArray(null);

			$array['books'] = $books['data'];
		}

		return $array;
	}

	/**
	 * Attach books to the data set.
	 *
	 * @return $this
	 */
	public function withBooks()
	{
		$this->loadBooks = true;

		return $this;
	}
}
