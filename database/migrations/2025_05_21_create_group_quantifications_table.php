<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('group_quantifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_basic_info_id')->constrained('group_basic_infos'); // 添加小组外键关联
            $table->text('content');
            $table->integer('score');
            $table->dateTime('time');
            $table->string('recorder');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_quantifications');
    }
};