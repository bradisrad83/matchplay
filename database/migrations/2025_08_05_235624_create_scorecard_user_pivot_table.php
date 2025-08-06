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
        Schema::create('scorecard_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scorecard_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('points')->nullable(); // individual points
            $table->boolean('winner')->nullable(); // did this user win?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scorecard_user', function (Blueprint $table) {
            $table->dropForeign(['scorecard_id', 'user_id']);
            $table->dropColumn('scorecard_id');
            $table->dropColumn('user_id');
        });
        Schema::dropIfExists('scorecard_user_pivot');
    }
};
