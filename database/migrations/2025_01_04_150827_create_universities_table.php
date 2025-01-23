<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversitiesTable extends Migration
    {
        /**
         * Jalankan migration.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('universities', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->decimal('spp', 15, 2);
                $table->string('akreditasi');
                $table->integer('dosen_s3');
                $table->timestamps();
            });
        }
    
        /**
         * Batalkan migration.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('universities');
        }
    }
