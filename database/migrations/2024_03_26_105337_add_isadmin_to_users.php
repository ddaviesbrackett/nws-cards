<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('isadmin')->default(false);
        });

        DB::update('update users u
                inner join users_groups ug on ug.user_id = u.id
                set u.isadmin = 1
                where ug.group_id = 1');

        Schema::rename('users_groups', 'zap_users_groups');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('isadmin');
        });

        Schema::rename('zap_users_groups', 'users_groups');
    }
};
