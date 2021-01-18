<?php

namespace App\Http\Controllers;

class TranslationController extends AbstractController
{
	public function index()
	{
		return response()
			->json(cache('i18n'));
	}
}
