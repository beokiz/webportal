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
        DB::statement("ALTER TABLE `training_proposals` MODIFY COLUMN `status` ENUM('email_not_confirmed', 'open', 'obsolete', 'reserved', 'confirmation_pending', 'confirmed') NOT NULL");
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
