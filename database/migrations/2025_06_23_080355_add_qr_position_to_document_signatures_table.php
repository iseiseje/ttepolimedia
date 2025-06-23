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
        Schema::table('document_signatures', function (Blueprint $table) {
            $table->integer('qr_page')->nullable();
            $table->integer('qr_x')->nullable();
            $table->integer('qr_y')->nullable();
            $table->integer('qr_canvas_width')->nullable();
            $table->integer('qr_canvas_height')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_signatures', function (Blueprint $table) {
            $table->dropColumn(['qr_page', 'qr_x', 'qr_y', 'qr_canvas_width', 'qr_canvas_height']);
        });
    }
};
