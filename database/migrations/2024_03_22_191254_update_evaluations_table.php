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
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('child_duration_in_kita')
                ->nullable()
                ->after('is_daz');

            $table->boolean('integration_status')
                ->default(false)
                ->after('child_duration_in_kita');

            $table->boolean('speech_therapy_status')
                ->default(false)
                ->after('integration_status');
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
