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
        Schema::table('classes', function (Blueprint $table) {
            $table->integer('enrolment');
            $table->dropColumn(['classsplit', 'pacsplit', 'tuitionsplit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('enrolment');
            $table->decimal('classsplit', 6, 2);
            $table->decimal('pacsplit', 6, 2);
            $table->decimal('tuitionsplit', 6, 2);
        });
    }
};
