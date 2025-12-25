<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->unique()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE karyawans MODIFY telepon VARCHAR(255) NULL");
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE karyawans ALTER COLUMN telepon DROP NOT NULL');
        }

        if ($driver === 'mysql') {
            DB::statement('UPDATE karyawans k JOIN users u ON u.email = k.email SET k.user_id = u.id WHERE k.user_id IS NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('UPDATE karyawans SET user_id = u.id FROM users u WHERE users.email = karyawans.email AND karyawans.user_id IS NULL');
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE karyawans MODIFY telepon VARCHAR(255) NOT NULL");
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE karyawans ALTER COLUMN telepon SET NOT NULL');
        }

        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
