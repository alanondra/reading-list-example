<?php

namespace App\Http\Controllers;

use Throwable;
use App\Http\Requests\PaginatedFormRequest;
use App\Http\Requests\Author\{
	StoreRequest,
	UpdateRequest
};
use App\Http\Resources\Book\{
	Collection,
	Resource,
};
use App\Models\Book;

class BookController extends AbstractController
{
	public function __construct()
	{
		$this->middleware(['auth.basic', 'verified'])
			->only(['store', 'update', 'destroy']);
	}

	public function index(PaginatedFormRequest $request)
	{
		$count = $request->input('count')
			?: (new Book())->getPerPage();

		$paginator = Book::paginate($count);

		return new Collection($paginator);
	}

	public function show(Book $book)
	{
		return (new Resource($book))->withAuthors();
	}

	public function store(StoreRequest $request)
	{
		try {
			$fill = [
				'isbn',
				'name',
				'description',
			];

			$book = new Book($request->only($fill));

			$book->save();

			$authorIds = $request->input('authors');

			foreach ($authorIds as $authorId) {
				$book->authors()->attach($authorId);
			}

			$book->save();

			return (new Resource($book))->withAuthors();
		} catch (Throwable $exc) {
			if (!$request->expectsJson()) {
				return back()
					->withInput($request->validated())
					->withException($exc);
			}

			throw $exc;
		}
	}

	public function update(Book $book, UpdateRequest $request)
	{
		//
	}

	public function destroy(Book $book)
	{
		$bookId = $book->getKey();

		$book->delete();

		return $bookId;
	}
}
