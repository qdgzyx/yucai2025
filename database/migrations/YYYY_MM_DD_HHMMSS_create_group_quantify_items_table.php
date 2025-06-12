<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('group_quantify_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('criteria');
            $table->integer('score');
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_quantify_items');
    }
};