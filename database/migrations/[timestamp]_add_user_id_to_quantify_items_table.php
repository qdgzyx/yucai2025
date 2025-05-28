<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quantify_items', function (Blueprint $table) {
            // 修改为可空字段并设置默认值
            $table->unsignedBigInteger('user_id')->after('id')->nullable();
        });

        // 新增数据填充步骤
        \DB::table('quantify_items')->update(['user_id' => \App\Models\User::first()->id]);

        Schema::table('quantify_items', function (Blueprint $table) {
            // 添加外键约束并设置为不可空
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('quantify_items', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};