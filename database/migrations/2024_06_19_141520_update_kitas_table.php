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
        Schema::table('kitas', function (Blueprint $table) {
            $table->dropColumn('provider_of_the_kita');

            $table->string('district')
                ->nullable()
                ->after('city');

            $table->enum('type', ['small', 'large'])
                ->default('small')
                ->after('id');

            $table->boolean('approved')
                ->default(false)
                ->after('type');

            $table->foreignId('operator_id')
                ->nullable()
                ->after('name')
                ->constrained('operators', 'id')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->unsignedBigInteger('num_pedagogical_staff')
                ->nullable()
                ->after('house_number');

            $table->text('notes')
                ->nullable()
                ->after('num_pedagogical_staff');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() : void
    {
        //
    }
};
