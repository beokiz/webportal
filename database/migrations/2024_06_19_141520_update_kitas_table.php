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
            // Drop column if it exists
            if (Schema::hasColumn('kitas', 'provider_of_the_kita')) {
                $table->dropColumn('provider_of_the_kita');
            }

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
        Schema::table('kitas', function (Blueprint $table) {
            // Drop foreign key constraint if it exists
            $table->dropForeign(['operator_id']);

            // Drop columns if they exist
            if (Schema::hasColumn('kitas', 'district')) {
                $table->dropColumn('district');
            }
            if (Schema::hasColumn('kitas', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('kitas', 'approved')) {
                $table->dropColumn('approved');
            }
            if (Schema::hasColumn('kitas', 'operator_id')) {
                $table->dropColumn('operator_id');
            }
            if (Schema::hasColumn('kitas', 'num_pedagogical_staff')) {
                $table->dropColumn('num_pedagogical_staff');
            }
            if (Schema::hasColumn('kitas', 'notes')) {
                $table->dropColumn('notes');
            }

            // Add the column back if necessary
            $table->string('provider_of_the_kita')->nullable();
        });
    }
};
