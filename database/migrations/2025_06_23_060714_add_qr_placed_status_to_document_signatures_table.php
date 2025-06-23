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
        // Since SQLite doesn't support ALTER TABLE for enums, we need to recreate the table
        Schema::table('document_signatures', function (Blueprint $table) {
            // First, drop the existing enum constraint
            $table->dropColumn('status');
        });

        Schema::table('document_signatures', function (Blueprint $table) {
            // Add the status column with the new enum values
            $table->enum('status', ['pending', 'qr_placed', 'signed', 'rejected'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_signatures', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('document_signatures', function (Blueprint $table) {
            $table->enum('status', ['pending', 'signed', 'rejected'])->default('pending');
        });
    }
};
