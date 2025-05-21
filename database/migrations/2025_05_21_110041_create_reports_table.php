<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	public function up()
	{
		Schema::create('reports', function(Blueprint $table) {
            $table->increments('id');
            $table->date('date')->index();
            $table->integer('banji_id')->index();
            $table->integer('total_expected');
            $table->integer('total_actual');
            $table->integer('sick_leave_count')->default(0)->nullable();
            $table->json('sick_list')->nullable();
            $table->integer('personal_leave_count')->default(0)->nullable();
            $table->json('personal_list')->nullable();
            $table->integer('absent_count')->default(0);
            $table->json('absent_list')->nullable();
            $table->tinyInteger('report_status')->default(0)->index();
            $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('reports');
	}
};
