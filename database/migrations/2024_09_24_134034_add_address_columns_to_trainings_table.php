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
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('city')
                ->nullable()
                ->after('status');

            $table->string('zip_code', 20)
                ->nullable()
                ->after('status');

            $table->string('house_number')
                ->nullable()
                ->after('status');

            $table->string('street')
                ->nullable()
                ->after('status');

            $table->string('location')
                ->nullable()
                ->after('status');
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
