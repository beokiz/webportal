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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('kita_id')
                ->nullable()
                ->constrained('kitas', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('age', ['2.5', '4.5'])->nullable();
            $table->boolean('is_daz')->default(false);
            $table->json('data')->nullable();
            $table->timestamp('finished_at')->nullable();
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
        Schema::dropIfExists('evaluations');
    }
};
