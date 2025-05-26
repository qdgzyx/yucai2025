<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	public function up()
	{
		Schema::create('assignments', function(Blueprint $table) {
    $table->increments('id');
    $table->foreignId('subject_id')->constrained()->index();
    $table->text('content');
    $table->string('attachment')->nullable();
    $table->foreignId('user_id')->constrained('users')->index()->name('fk_assignments_user_id'); // 关键修正
    $table->datetime('publish_at');
    $table->datetime('deadline');
    $table->timestamps();
        });
	}

	public function down()
	{
		Schema::drop('assignments');
	}
};
