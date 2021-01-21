<?php

namespace App\Http\Resources\ReadingListItem;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'data' => Resource::collection($this->collection),
		];
	}
}
