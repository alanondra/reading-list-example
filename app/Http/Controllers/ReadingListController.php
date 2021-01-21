<?php

namespace App\Http\Controllers;

use Throwable;
use App\Exceptions\NotImplementedException;
use App\Models\ReadingList;
use App\Http\Requests\PaginatedFormRequest;
use App\Http\Requests\ReadingList\{
	StoreRequest,
	UpdateRequest
};
use App\Http\Resources\ReadingList\{
	Collection,
	Resource,
};

class ReadingListController extends AbstractController
{
	public function index(PaginatedFormRequest $request)
	{
		$user = $request->user();

		$count = $request->input('count')
			?: (new ReadingList())->getPerPage();

		$paginator = $user
			->readingLists()
			->paginate($count);

		return new Collection($paginator);
	}

	public function show(ReadingList $readingList)
	{
		return (new Resource($readingList))->withItems();
	}

	public function store(StoreRequest $request)
	{
		try {
			$readingList = new ReadingList($request->validated());

			$readingList->user()->associate($request->user());

			$readingList->save();

			$bookIds = $request->input('books', []);

			$itemBookId = $readingList
				->items()
				->getRelated()
				->book()
				->getForeignKeyName();

			foreach ($bookIds as $bookId) {
				$item = $readingList->items()->create([
					$itemBookId => $bookId,
				]);

				$item->save();
			}

			return new Resource($readingList);
		} catch (Throwable $exc) {
			if (!$request->expectsJson()) {
				return back()
					->withInput()
					->withException($exc);
			}

			throw $exc;
		}
	}

	public function update(UpdateRequest $request, ReadingList $readingList)
	{
		throw new NotImplementedException();
	}

	public function destroy(ReadingList $readingList)
	{
		throw new NotImplementedException();
	}
}
