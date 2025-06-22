<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code')->unique();
            $table->foreignId('document_signature_id')->constrained();
            $table->foreignId('dosen_id')->constrained('users');
            $table->string('document_name');
            $table->timestamp('signed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('verifications');
    }
}; 