<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Author;
use App\Http\Requests\PaginatedFormRequest;
use App\Http\Requests\Author\{
	StoreRequest,
	UpdateRequest
};
use App\Http\Resources\Author\{
	Collection,
	Resource,
};

class AuthorController extends AbstractController
{
	public function __construct()
	{
		$this->middleware(['auth.basic', 'verified'])
			->only(['store', 'update', 'destroy']);
	}

	public function index(PaginatedFormRequest $request)
	{
		$count = $request->input('count')
			?: (new Author())->getPerPage();

		$paginator = Author::paginate($count);

		return new Collection($paginator);
	}

	public function show(Author $author)
	{
		return (new Resource($author))->withBooks();
	}

	public function store(StoreRequest $request)
	{
		try {
			$author = new Author($request->validated());

			$author->save();

			return new Resource($author);
		} catch (Throwable $exc) {
			if (!$request->expectsJson()) {
				return back()
					->withInput()
					->withException($exc);
			}

			throw $exc;
		}
	}

	public function update(UpdateRequest $request, Author $author)
	{
		try {
			foreach ($request->validated() as $prop => $value) {
				$author->$prop = $value;
			}

			$author->save();

			return new Resource($author);
		} catch (Throwable $exc) {
			if (!$request->expectsJson()) {
				return back()
					->withInput()
					->withException($exc);
			}

			throw $exc;
		}
	}

	public function destroy(Author $author)
	{
		//
	}
}
