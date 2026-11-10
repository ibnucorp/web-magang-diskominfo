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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // user yang submit
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->text('komentar')->nullable(); // komentar dari admin
            $table->enum('status_pendaftaran', [
                'menunggu_verifikasi',
                'revisi',
                'menunggu_keputusan',
                'diterima',
                'ditolak'
            ])->default('menunggu_verifikasi');
            $table->string('surat_pengantar'); // path file
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
