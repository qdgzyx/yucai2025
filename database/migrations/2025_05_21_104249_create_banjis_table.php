<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up() {
        Schema::create('banjis', function(Blueprint $table) {
            $table->id();
            // 修正外键定义（显式指定关联表名）
            $table->foreignId('grade_id')->constrained('grades');
            $table->string('name')->index();
            $table->integer('student_count');
            // 方案1：自动生成唯一名称
            $table->foreignId('user_id')->constrained('users');
            // 方案2：手动指定唯一名称
            // $table->foreignId('user_id');
            // $table->foreign('user_id', 'fk_banjis_user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('banjis');
    }
};