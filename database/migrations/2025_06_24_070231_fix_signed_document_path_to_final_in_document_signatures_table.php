<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all signed_document_path for signed documents to use signed_final_ prefix
        DB::table('document_signatures')
            ->where('status', 'signed')
            ->whereNotNull('signed_document_path')
            ->where('signed_document_path', 'like', 'signed_documents/signed_%')
            ->where('signed_document_path', 'not like', 'signed_documents/signed_final_%')
            ->update([
                'signed_document_path' => DB::raw("REPLACE(signed_document_path, 'signed_', 'signed_final_')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, revert back to signed_ prefix
        DB::table('document_signatures')
            ->where('status', 'signed')
            ->whereNotNull('signed_document_path')
            ->where('signed_document_path', 'like', 'signed_documents/signed_final_%')
            ->update([
                'signed_document_path' => DB::raw("REPLACE(signed_document_path, 'signed_final_', 'signed_')")
            ]);
    }
}; 