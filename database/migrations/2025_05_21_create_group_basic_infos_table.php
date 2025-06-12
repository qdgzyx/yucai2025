<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('group_basic_infos', function (Blueprint $table) {
            $table->id(); // 自动生成序号
            $table->string('name');
            $table->foreignId('banji_id')->constrained('banjis');
            $table->string('leader');       // 新增组长字段
            $table->text('members');        // 新增组员字段
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_basic_infos');
    }
};