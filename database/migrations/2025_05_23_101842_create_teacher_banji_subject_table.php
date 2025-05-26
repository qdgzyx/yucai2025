<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_banji_subject', function (Blueprint $table) {
        $table->id(); // 新增主键
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('subject_id')->constrained();
        $table->foreignId('banji_id')->constrained();
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_banji_subject');
    }
};
