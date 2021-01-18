<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ config('app.name') }}</title>
		<link rel="stylesheet" type="text/css" href="{{ mix('/assets/build/css/app.css') }}"/>
		<script type="text/javascript" src="{{ mix('/assets/build/js/manifest.js') }}"></script>
		<script type="text/javascript" src="{{ mix('/assets/build/js/vendor.js') }}"></script>
		<script type="text/javascript" src="{{ mix('/assets/build/js/app.js') }}"></script>
	</head>
	<body>
		<div id="app"></div>
	</body>
</html>