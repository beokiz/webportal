<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() : void
    {
        Schema::create('yearly_evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->foreignId('kita_id')
                ->constrained('kitas', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('evaluations_without_daz_2_total_per_year')
                ->default(0);
            $table->unsignedBigInteger('evaluations_without_daz_4_total_per_year')
                ->default(0);
            $table->unsignedBigInteger('evaluations_with_daz_2_total_per_year')
                ->default(0);
            $table->unsignedBigInteger('evaluations_with_daz_4_total_per_year')
                ->default(0);
            $table->unsignedBigInteger('evaluations_2_total_per_year')
                ->default(0);
            $table->unsignedBigInteger('evaluations_4_total_per_year')
                ->default(0);
            $table->unsignedBigInteger('children_2_born_per_year')
                ->default(0);
            $table->unsignedBigInteger('children_4_born_per_year')
                ->default(0);
            $table->unsignedBigInteger('children_2_with_german_lang')
                ->default(0);
            $table->unsignedBigInteger('children_4_with_german_lang')
                ->default(0);
            $table->unsignedBigInteger('children_2_with_foreign_lang')
                ->default(0);
            $table->unsignedBigInteger('children_4_with_foreign_lang')
                ->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('yearly_evaluations');
    }
};
