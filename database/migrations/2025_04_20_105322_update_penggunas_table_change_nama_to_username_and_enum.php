<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penggunas', function (Blueprint $table) {
            // Ganti kolom nama jadi username
            $table->renameColumn('nama', 'username');
        });

        // Ubah nilai 'staff' jadi 'admin' dulu biar enum bisa diubah
        DB::table('penggunas')->where('jenis_pengguna', 'staff')->update(['jenis_pengguna' => 'admin']);

        // Ubah enum jenis_pengguna
        DB::statement("ALTER TABLE penggunas MODIFY jenis_pengguna ENUM('owner', 'admin') NOT NULL DEFAULT 'owner'");
    }

    public function down(): void
    {
        Schema::table('penggunas', function (Blueprint $table) {
            $table->renameColumn('username', 'nama');
        });

        // Balikin enum ke semula
        DB::statement("ALTER TABLE penggunas MODIFY jenis_pengguna ENUM('owner', 'staff') NOT NULL DEFAULT 'owner'");
    }
};
