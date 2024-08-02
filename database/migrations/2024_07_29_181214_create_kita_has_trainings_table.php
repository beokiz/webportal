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
        Schema::create('kita_has_trainings', function (Blueprint $table) {
            $table->foreignId('training_id')
                ->constrained('trainings', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('kita_id')
                ->constrained('kitas', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->primary(['training_id', 'kita_id'], 'kita_has_trainings_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('kita_has_trainings');
    }
};
