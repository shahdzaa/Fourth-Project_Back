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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique()->nullable();
            $table->string('name')->nullable();
            $table->enum('type',['مستشفى','مدرسة','بناء سكني','جامع','كنيسة']);
            $table->boolean('is_legal');
            $table->integer('number_of_floors');
            $table->integer('number_of_floors_violating');
            $table->enum('structural_pattern',['إطار بيتوني','جدران بيتونية','حجري','خشبي','مختلط']);
            $table->integer('number_of_families_before_departure');
            $table->integer('number_of_families_after_departure');
            $table->enum('level_of_damage',[0,1,2,3,4]);
            $table->foreignId('neighbourhood_id')->constrained('neighbourhoods')->onDelete('cascade');
            $table->boolean('is_materials_from_the_neighborhood');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
