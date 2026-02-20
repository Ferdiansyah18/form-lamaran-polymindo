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
        Schema::create('pelamars', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tempat_tgl_lahir');
            $table->string('pendidikan_terakhir');
            $table->string('alamat_email');
            $table->string('agama');
            $table->string('nomor_ktp');
            $table->string('nomor_npwp');
            $table->string('nomor_rekening_bca');
            $table->string('nomor_handphone');
            $table->string('status_perkawinan');
            $table->text('alamat_domisili');
            $table->string('rt_rw_dom');
            $table->string('kelurahan_dom');
            $table->string('kecamatan_dom');
            $table->string('kabupaten_kota_dom');
            $table->text('alamat_ktp');
            $table->string('rt_rw_ktp');
            $table->string('kelurahan_ktp');
            $table->string('kecamatan_ktp');
            $table->string('kabupaten_kota_ktp');
            $table->string('foto_path')->nullable(); // Path to photo
            $table->string('file_excel_path')->nullable(); // Path to generated excel
            $table->string('nama_ayah');
            $table->string('nama_ibu_kandung');
            $table->string('nama_istri_suami');
            $table->string('nama_keluarga_serumah');
            $table->string('nomor_hp_keluarga');
            // Custom fields "Lain-lain"
            for ($i = 1; $i <= 9; $i++) {
                $table->string("lain_no_$i")->nullable();
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelamars');
    }
};
