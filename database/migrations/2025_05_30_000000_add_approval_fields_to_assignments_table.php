<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('status')->default('pending')
                ->comment('pending:待审核, approved:已通过, rejected:已驳回');

            // 新增两个审核人字段
            $table->unsignedBigInteger('group_leader_id')->nullable()
                ->comment('教研组长审核人ID');
            $table->unsignedBigInteger('director_id')->nullable()
                ->comment('级部主任审核人ID');
                
            $table->timestamp('approval_time')->nullable();
            $table->text('reject_reason')->nullable();

            // 添加外键约束
            $table->foreign('group_leader_id')->references('id')->on('users');
            $table->foreign('director_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn([
                'status', 
                'group_leader_id', // 新增字段
                'director_id',     // 新增字段
                'approval_time', 
                'reject_reason'
            ]);
            
            // 删除外键约束
            $table->dropForeign(['group_leader_id']);
            $table->dropForeign(['director_id']);
        });
    }
};