<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('builds', function($table)
    {
        $table->increments('id');
        $table->string('build_title', 100);
        $table->string('build_creator', 16);
        $table->string('build_image', 500);
        $table->timestamps();
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('builds');
	}

}
