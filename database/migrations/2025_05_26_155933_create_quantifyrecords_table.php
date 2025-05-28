<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// 修改：修正类名与文件名大小写匹配
return new class extends Migration
{
    public function up()
    {
        Schema::create('quantify_records', function (Blueprint $table) {
            $table->increments('id');
            
            // 添加semester_id字段
            $table->unsignedBigInteger('semester_id')->index();
            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('cascade');
                
            // 添加banji_id字段
            $table->unsignedBigInteger('banji_id')->index();
            $table->foreign('banji_id', 'fk_quantify_records_banji_id')
                ->references('id')
                ->on('banjis')
                ->onDelete('cascade');
            
            $table->unsignedBigInteger('quantify_item_id')->index();
            $table->foreign('quantify_item_id', 'fk_quantify_records_item_id')
                ->references('id')
                ->on('quantify_items')
                ->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->users()->index();
            $table->float('score');
            $table->text('remark')->nullable();
            $table->timestamp('assessed_at');
            $table->ipAddress('ip_address')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quantify_records');
    }
};
