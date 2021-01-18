<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();

			$table->string('email', 256)
				->unique()
				->charset('utf8')
				->collation('utf8_unicode_ci');

			$table->string('login', 256)
				->nullable(false)
				->unique();

			$table->string('password', 256)
				->nullable(false);

			$table->string('name', 256)
				->nullable();

			$table->text('two_factor_secret')
				->nullable();

			$table->text('two_factor_recovery_codes')
				->nullable();

			$table->rememberToken();

			$table->timestamp('email_verified_at')
				->nullable();

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
		Schema::dropIfExists('users');
	}
}
