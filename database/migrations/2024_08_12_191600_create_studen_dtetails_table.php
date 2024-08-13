<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('studen_dtetails', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('subject');
            $table->string('mark');
            $table->unsignedTinyInteger('is_delete')->default(0)->comment('0=active, 1=deleted');
            $table->integer('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studen_dtetails');
    }
};
