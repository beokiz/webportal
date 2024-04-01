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
            $table->string('provider_of_the_kita')
                ->nullable()
                ->after('name');

            $table->string('city')
                ->nullable()
                ->after('provider_of_the_kita');

            $table->unsignedBigInteger('number')
                ->nullable()
                ->after('city');

            $table->string('street')
                ->nullable()
                ->after('number');

            $table->string('house_number')
                ->nullable()
                ->after('street');

            $table->text('additional_info')
                ->nullable()
                ->after('house_number');
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
