<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	public function up()
	{
		Schema::create('grades', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->integer('year');
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('grades');
	}
};
