<?php

namespace App\Http\Resources\ReadingListItem;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Book\Resource as BookResource;

class Resource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		$this->resource->loadMissing(['book']);

		$array = [
			'finished_at' => $this->finishedAt,
			'book' => new BookResource($this->book),
		];

		return $array;
	}
}
