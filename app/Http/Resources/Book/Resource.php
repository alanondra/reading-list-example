<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Author\Collection as AuthorCollection;

class Resource extends JsonResource
{
	protected bool $loadAuthors = false;

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
			'isbn' => $this->isbn,
			'name' => $this->name,
			'description' => $this->description,
		];

		if ($this->loadAuthors) {
			$this->resource->loadMissing(['authors']);

			$authors = (new AuthorCollection($this->resource->authors))->toArray(null);

			$array['authors'] = $authors['data'];
		}

		return $array;
	}

	/**
	 * Attach authors to the data set.
	 *
	 * @return $this
	 */
	public function withAuthors()
	{
		$this->loadAuthors = true;

		return $this;
	}
}
