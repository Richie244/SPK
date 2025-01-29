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
        if (!Schema::hasTable('bobot_kriteria')) {
            Schema::create('bobot_kriteria', function (Blueprint $table) {
                $table->id();
                $table->string('kriteria')->unique();
                $table->float('default_bobot')->default(0);
                $table->float('custom_bobot')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_kriteria');
    }
};
