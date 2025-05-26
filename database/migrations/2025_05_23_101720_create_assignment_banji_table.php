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
        Schema::create('assignment_banji', function (Blueprint $table) { 
        $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
        $table->foreignId('banji_id')->constrained('banjis'); 
        $table->unique(['assignment_id', 'banji_id']);
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
        Schema::dropIfExists('assignment_banji');
    }
};
