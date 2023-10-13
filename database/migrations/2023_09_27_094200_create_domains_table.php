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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('abbreviation');
            $table->string('name');
            $table->integer('order')->default(0);
            $table->unsignedSmallInteger('age_2_red_threshold');
            $table->unsignedSmallInteger('age_2_red_threshold_daz');
            $table->unsignedSmallInteger('age_2_yellow_threshold');
            $table->unsignedSmallInteger('age_2_yellow_threshold_daz');
            $table->unsignedSmallInteger('age_4_red_threshold');
            $table->unsignedSmallInteger('age_4_red_threshold_daz');
            $table->unsignedSmallInteger('age_4_yellow_threshold');
            $table->unsignedSmallInteger('age_4_yellow_threshold_daz');
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
        Schema::dropIfExists('domains');
    }
};
