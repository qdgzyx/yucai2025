<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	public function up()
	{
		Schema::create('semesters', function(Blueprint $table) {
            $table->increments('id');
            $table->foreignId('academic_id')->constrained('academics')
            ->name('semesters_academic_id_unique');
            $table->string('name')->index();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->boolean('is_current')->default=(0);
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('semesters');
	}
};
