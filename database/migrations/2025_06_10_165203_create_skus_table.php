<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Hinban;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->integer('seq');
            $table->foreignId('hinban_id')->constrained()->onDelete('cascade');
            $table->bigInteger('sku_code')->nullable();
            $table->integer('col_id')->nullable();
            $table->integer('size_id')->nullable();
            $table->integer('local_cur_price')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('sku_image')->nullable();
            $table->timestamps();

            // $table->foreign('hinban_id')->references('hinban_id')->on('hinbans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }


};
