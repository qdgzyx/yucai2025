<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quantify_items', function (Blueprint $table) {
            $table->id();
            // 修改：确保 semester_id 的数据类型与 semesters 表中的 id 一致
            $table->unsignedBigInteger('semester_id');
            $table->string('name')->index();
            $table->string('type')->index();
            $table->float('score');
            $table->json('criteria');
            $table->boolean('status');
            $table->timestamps();

            // 添加外键约束，并确保级联删除逻辑正确
            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quantify_items');
    }
};