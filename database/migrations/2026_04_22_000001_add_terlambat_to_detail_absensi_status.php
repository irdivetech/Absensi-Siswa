<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE detail_absensi MODIFY COLUMN status ENUM('hadir', 'alpha', 'izin', 'sakit', 'dispen', 'izin_token', 'terlambat') DEFAULT 'alpha'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE detail_absensi MODIFY COLUMN status ENUM('hadir', 'alpha', 'izin', 'sakit', 'dispen', 'izin_token') DEFAULT 'alpha'");
    }
};
