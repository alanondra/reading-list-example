<?php

namespace App\Http\Resources\ReadingList;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ReadingListItem\Resource as ItemResource;

class Resource extends JsonResource
{
	protected bool $loadItems = false;

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
		];

		if ($this->loadItems) {
			$this->resource->loadMissing(['items.book']);

			$array['items'] = ItemResource::collection($this->items);
		}

		return $array;
	}

	/**
	 * Attach items to the data set.
	 *
	 * @return $this
	 */
	public function withItems()
	{
		$this->loadItems = true;

		return $this;
	}
}
