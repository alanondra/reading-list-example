<?php

namespace App\Support\Mixins\Database\Schema;

/**
 * @mixin \Illuminate\Database\Schema\Blueprint
 */
class Blueprint
{
	/**
	 * Set up the userstamps macro.
	 *
	 * @return function
	 */
	public function userstamp()
	{
		return function ($column, int $precision = 0) {
			$this->bigInteger('created_by', $precision)
				->unsigned()
				->nullable();

			$this->bigInteger('updated_by', $precision)
				->unsigned()
				->nullable();
		};
	}

	/**
	 * Set up the userstamps macro.
	 *
	 * @return function
	 */
	public function userstamps()
	{
		return function (int $precision = 0) {
			$this->bigInteger('created_by', $precision)
				->unsigned()
				->nullable();

			$this->bigInteger('updated_by', $precision)
				->unsigned()
				->nullable();
		};
	}

	/**
	 * Set up the userDeletes macro.
	 *
	 * @return function
	 */
	public function userDeletes()
	{
		/**
		 * Add a "deleted at" timestamp for the table.
		 *
		 * @param  int  $precision
		 *
		 * @return \Illuminate\Database\Schema\ColumnDefinition
		 */
		return function (int $precision = 0) {
			return $this->bigInteger('deleted_by', $precision)
				->unsigned()
				->nullable();
		};
	}
}
