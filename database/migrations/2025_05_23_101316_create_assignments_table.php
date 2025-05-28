<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	public function up()
	{
    Schema::create('assignments', function(Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('subject_id'); // 确保数据类型与 subjects 表中的 id 字段一致
        $table->foreign('subject_id', 'fk_assignments_subject_id')
            ->references('id')
            ->on('subjects')
            ->onDelete('cascade');
        $table->text('content');
        $table->string('attachment')->nullable();
        $table->foreignId('user_id')->constrained('users')->index()->name('fk_assignments_user_id');
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
