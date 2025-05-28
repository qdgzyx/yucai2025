<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	public function up()
	{
		Schema::create('quantify_types', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->string('code');
            $table->string('name');
            $table->decimal('weight');
            $table->Integer('order');
            $table->text('requirements');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('quantify_types');
	}
};
