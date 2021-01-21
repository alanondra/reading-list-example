<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
	Author,
	Book,
	ReadingList,
};

class DashboardController extends AbstractController
{
	public function index(Request $request)
	{
		$data = [];

		$data['books'] = Book::orderBy('created_at', 'desc')
			->take(3)
			->get();

		$data['authors'] = Author::orderBy('created_at', 'desc')
			->take(3)
			->get();

		if ($request->user()) {
			$data['lists'] = ReadingList::with(['books'])
				->orderBy('created_at', 'desc')
				->take(3)
				->get();
		}

		return response()
			->json([
				'data' => $data
			]);
	}
}
