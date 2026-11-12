<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom ke user_tokens
        Schema::table('user_tokens', function (Blueprint $table) {
            $table->string('redemption_code', 10)->nullable()->unique()->after('status');
            $table->unsignedBigInteger('validated_by')->nullable()->after('id_absensi_used');
            $table->foreign('validated_by')->references('id_guru')->on('guru')->nullOnDelete();
            $table->timestamp('validated_at')->nullable()->after('validated_by');
            $table->text('usage_note')->nullable()->after('validated_at');
        });

        // 2. Update enum status: tambah PENDING
        DB::statement("ALTER TABLE user_tokens MODIFY COLUMN status ENUM('AVAILABLE','PENDING','USED','EXPIRED') DEFAULT 'AVAILABLE'");

        // 3. Tambah flag terlambat ke detail_absensi
        Schema::table('detail_absensi', function (Blueprint $table) {
            $table->boolean('is_late')->default(false)->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('user_tokens', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn(['redemption_code', 'validated_by', 'validated_at', 'usage_note']);
        });

        DB::statement("ALTER TABLE user_tokens MODIFY COLUMN status ENUM('AVAILABLE','USED','EXPIRED') DEFAULT 'AVAILABLE'");

        Schema::table('detail_absensi', function (Blueprint $table) {
            $table->dropColumn('is_late');
        });
    }
};
