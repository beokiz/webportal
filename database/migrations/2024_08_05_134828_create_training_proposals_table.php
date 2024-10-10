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
        Schema::create('training_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('multi_id')
                ->nullable()
                ->constrained('users', 'id')
                ->onUpdate('set null')
                ->onDelete('set null');
            $table->date('first_date')->nullable();
            $table->date('second_date')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('participant_count')->default(0);
            $table->enum('status', ['open', 'obsolete', 'reserved', 'confirmation_pending', 'confirmed'])
                ->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        Schema::dropIfExists('training_proposals');
    }
};
