<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadingListItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reading_list_items', function (Blueprint $table) {
			$table->id();

			$table->integer('reading_list_id');

			$table->integer('book_id');

			$table->dateTime('finished_at')->nullable();

			$table->timestamps();

			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('reading_list_items');
	}
}
