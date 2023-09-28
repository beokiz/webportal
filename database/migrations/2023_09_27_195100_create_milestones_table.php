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
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdomain_id')
                ->constrained('subdomains', 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('abbreviation');
            $table->string('title');
            $table->text('text');
            $table->integer('order')->default(0);
            $table->double('emphasis');
            $table->double('emphasis_daz');
            $table->enum('age', ['2.5', '4.5'])->nullable();
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
        Schema::dropIfExists('milestones');
    }
};
