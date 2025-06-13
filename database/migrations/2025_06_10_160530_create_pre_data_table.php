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
        Schema::create('pre_data', function (Blueprint $table) {
            $table->id();
            $table->integer('year_code');
            $table->integer('shohin_gun');
            $table->integer('brand_id');
            $table->integer('seireki_unit');
            $table->integer('unit_id');
            $table->string('face_code');
            $table->integer('hinban_id');
            $table->integer('kyotu_hinban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_data');
    }
};
