<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('created_at')->default('2014-01-01 00:00:00')->change(); //newer mysql disallows the previous all-zero defaults
            $table->timestamp('updated_at')->default('2014-01-01 00:00:00')->change();
            $table->dropColumn([
                'pac',
                'tuitionreduction',
                'marigold',
                'daisy',
                'sunflower',
                'bluebell',
                'class_1',
                'class_2',
                'class_3',
                'class_4',
                'class_5',
                'class_6',
                'class_7',
                'class_8',
                'referrer',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('referrer',  255);
            $table->decimal('pac', 6, 2);
            $table->decimal('tuitionreduction', 6, 2);
            $table->decimal('marigold', 6, 2);
            $table->decimal('daisy', 6, 2);
            $table->decimal('sunflower', 6, 2);
            $table->decimal('bluebell', 6, 2);
            $table->decimal('class_1', 6, 2);
            $table->decimal('class_2', 6, 2);
            $table->decimal('class_3', 6, 2);
            $table->decimal('class_4', 6, 2);
            $table->decimal('class_5', 6, 2);
            $table->decimal('class_6', 6, 2);
            $table->decimal('class_7', 6, 2);
            $table->decimal('class_8', 6, 2);
        });
    }
};
