<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends AbstractController
{
	public function index(Request $request)
	{
		return view('app');
	}
}
