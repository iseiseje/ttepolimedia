<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('users');
            $table->foreignId('dosen_id')->constrained('users');
            $table->string('document_path');
            $table->string('original_filename');
            $table->enum('status', ['pending', 'signed', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_signatures');
    }
}; 