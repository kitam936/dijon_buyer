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
        Schema::create('hinbans', function (Blueprint $table) {
            $table->id();
            $table->integer('brand_id');
            $table->integer('unit_id');
            $table->string('face_code');
            // $table->integer('hinban_id')->index();
            $table->string('prod_code')->nullable();
            $table->string('hinban_name');
            $table->text('hinban_info')->nullable();
            $table->string('mix_rate')->nullable();
            $table->integer('season_code');
            $table->integer( 'year_code');
            $table->integer('shohin_gun');
            $table->integer('kizoku_g');
            $table->integer('seireki_unit');
            $table->integer('kyotu_hinban');
            $table->integer( 'vendor_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hinbans');
    }
};
