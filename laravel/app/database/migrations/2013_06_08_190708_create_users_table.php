<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('username', 64)->unique();
			$table->string('password', 64);
			$table->string('email', 100)->unique();
			$table->integer('isAdmin');
			$table->timestamps();
			// primary key is id, second index on username
			$table->index('username');
		});

		// make a default admin account
		DB::table('users')->insert(array(
			'id'		=> 1,
			'username' 	=> 'admin',
			'password'	=> Hash::make('admin'),
			'email'		=> 'pasta@pastacode.com',
			'isAdmin'	=> 1,
			'created_at'=> DB::raw('NOW()'),
			'updated_at'=> DB::raw('NOW()'),
		));
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