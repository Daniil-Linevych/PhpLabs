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
        Schema::table('exhibits', function (Blueprint $table) {
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('creation_year')->nullable();
            $table->string('author');
            $table->foreignId('exhibition_id')->nullable()->constrained('exhibitions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibits', function (Blueprint $table) {
            Schema::dropIfExists('exhibits');
        });
    }
};
