<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

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
		return [
			'email' => $this->email,
			'login' => $this->login,
			'name' => $this->name,
		];
	}
}
