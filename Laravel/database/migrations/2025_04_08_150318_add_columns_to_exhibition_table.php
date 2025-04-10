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
        Schema::table('exhibitions', function (Blueprint $table) {
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
        });

        Schema::create('exhibition_staff', function (Blueprint $table) {
            $table->foreignId('exhibition_id')->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitions', function (Blueprint $table) {
            Schema::dropIfExists('exhibition_staff');
            Schema::dropIfExists('exhibitions');
        });
    }
};
