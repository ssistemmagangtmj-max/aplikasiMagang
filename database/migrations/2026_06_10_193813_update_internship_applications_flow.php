<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('internship_applications', function (Blueprint $table) {
            $table->string('acceptance_proof_file')->nullable()->after('letter_file');
        });

        // Migrate existing status to avoid truncation/errors when altering enum
        DB::table('internship_applications')->where('status', 'processing')->update(['status' => 'pending']);
        DB::table('internship_applications')->where('status', 'approved_admin')->update(['status' => 'surat_diterbitkan']);

        // Alter enum to include old and new statuses just in case, but defaulting to pending
        DB::statement("ALTER TABLE internship_applications MODIFY COLUMN status ENUM('pending', 'processing', 'approved_admin', 'surat_diterbitkan', 'bukti_diunggah', 'bukti_ditolak', 'approved', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum back (might cause error if records exist with new values, so just a best-effort approach)
        DB::statement("ALTER TABLE internship_applications MODIFY COLUMN status ENUM('pending', 'processing', 'approved_admin', 'approved', 'rejected') DEFAULT 'pending'");

        Schema::table('internship_applications', function (Blueprint $table) {
            $table->dropColumn('acceptance_proof_file');
        });
    }
};
