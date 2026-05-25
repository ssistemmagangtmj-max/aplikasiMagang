<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('id_pengguna', 8, 0)->unique(); // NIM for Mahasiswa, NIDN for Dosen, NIP for Admin
            $table->enum('role', ['mahasiswa', 'admin', 'kops', 'dosen'])->default('mahasiswa');
            $table->enum('jurusan', [
                'Teknik Informatika dan Komputer',
                'Teknik Elektro',
                'Administrasi Niaga'
            ])->nullable();
            $table->enum('prodi', [
                'D4 Teknik Multimedia dan Jaringan',
                'D4 Teknik Komputer dan Jaringan',
                'D4 Teknik Listrik',
                'D4 Teknologi Rekayasa Sistem Elektronika',
                'D3 Administrasi Bisnis',
                'D4 Manajemen Bisnis Jasa Pariwisata'
            ])->nullable();
            $table->string('password');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->decimal('id_pengguna', 8, 0)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
