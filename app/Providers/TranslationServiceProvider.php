<?php

namespace App\Providers;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$ttl = 3600 * 24;

		Cache::remember($this->getCacheKey(), $ttl, Closure::fromCallable([$this, 'getAllTranslations']));
	}

	/**
	 * Get the name of the cache entry for the translation table.
	 *
	 * @return string
	 */
	protected function getCacheKey(): string
	{
		return 'i18n';
	}

	/**
	 * Get the list of available languages for translation.
	 *
	 * @return array
	 */
	protected function getLanguages(): array
	{
		$path = $this->getLanguagePath();

		return array_values(array_filter(
			scandir($path),
			function ($file) {
				return !empty($file) && !str_starts_with($file, '.');
			}
		));
	}

	/**
	 * Get the translation path of a given language, or all languages if null.
	 *
	 * @param  string|null  $language
	 *
	 * @return string
	 */
	protected function getLanguagePath(?string $language = null): string
	{
		return (!empty($language))
			? resource_path("lang/{$language}")
			: resource_path('lang');
	}

	/**
	 * Get the translations for a given language.
	 *
	 * @param  string  $language
	 *
	 * @return array
	 */
	protected function getTranslations(string $language): array
	{
		$path = $this->getLanguagePath($language);

		return collect(File::allFiles($path))
			->flatMap(function ($file) use ($language) {
				$key = ($translation = $file->getBasename('.php'));

				return [$key => trans($translation, [], $language)];
			})
			->toArray();
	}

	/**
	 * Get the full translation table.
	 *
	 * @return array
	 */
	protected function getAllTranslations(): array
	{
		$languages = $this->getLanguages();

		$translations = [];

		foreach ($languages as $lang) {
			$translations[$lang] = $this->getTranslations($lang);
		}

		return $translations;
	}
}
