<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Pelamar;

class PelamarFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_submission_saves_to_database_and_generates_excel()
    {
        Storage::fake('public');
        
        // Ensure template exists for test (mocking real path is tricky in integration tests without vfsStream, 
        // but we can just use a dummy file in the real path if needed, or rely on the actual file existing)
        
        $data = [
            'nama' => 'John Doe',
            'tempat_tgl_lahir' => 'Jakarta, 01-01-1990',
            'pendidikan_terakhir' => 'S1 Teknik Informatika',
            'alamat_email' => 'john@example.com',
            'agama' => 'Islam',
            'nomor_ktp' => '1234567890123456',
            'nomor_npwp' => '12.345.678.9-012.345',
            'nomor_rekening_bca' => '1234567890',
            'nomor_handphone' => '081234567890',
            'status_perkawinan' => 'Menikah',
            'alamat_domisili' => 'Jl. Test No. 123',
            'rt_rw_dom' => '01/02',
            'kelurahan_dom' => 'Kelurahan Test',
            'kecamatan_dom' => 'Kecamatan Test',
            'kabupaten_kota_dom' => 'Kota Test',
            'alamat_ktp' => 'Jl. KTP No. 123',
            'rt_rw_ktp' => '03/04',
            'kelurahan_ktp' => 'Kelurahan KTP',
            'kecamatan_ktp' => 'Kecamatan KTP',
            'kabupaten_kota_ktp' => 'Kota KTP',
            'foto' => UploadedFile::fake()->image('avatar.jpg'),
            
            // Related Data
            'nama_perusahaan' => ['PT A', 'PT B', ''],
            'bagian_jabatan' => ['Staff', 'Manager', ''],
            'mulai_kerja' => ['2010', '2015', ''],
            'selesai_kerja' => ['2014', '2020', ''],
            'alasan_keluar' => ['Resign', 'Contract End', ''],
            
            'jenis_keahlian' => ['PHP', 'Laravel', ''],
            'penjelasan_skill' => ['Expert', 'Intermediate', ''],
            
            'nama_anak' => ['Anak 1', 'Anak 2'],
            
            'nama_ayah' => 'Ayah Test',
            'nama_ibu_kandung' => 'Ibu Test',
            'nama_istri_suami' => 'Istri Test',
            'nama_keluarga_serumah' => 'Keluarga Test',
            'nomor_hp_keluarga' => '08987654321',
            
            // Lain-lain
            'lain_no_1' => 'Jawaban 1',
            'lain_no_2' => 'Jawaban 2',
            'lain_no_3' => 'Jawaban 3',
            'lain_no_4' => 'Jawaban 4',
            'lain_no_5' => 'Jawaban 5',
            'lain_no_6' => 'Jawaban 6',
            'lain_no_7' => 'Jawaban 7',
            'lain_no_8' => 'Jawaban 8',
            'lain_no_9' => 'Jawaban 9',
        ];

        $response = $this->post(route('generate'), $data);

        // Check redirection success
        $response->assertSessionHas('success');
        $response->assertRedirect();

        // Check Database
        $this->assertDatabaseHas('pelamars', [
            'nama' => 'John Doe',
            'alamat_email' => 'john@example.com',
        ]);

        $pelamar = Pelamar::where('alamat_email', 'john@example.com')->first();
        
        $this->assertDatabaseHas('riwayat_pekerjaans', [
            'pelamar_id' => $pelamar->id,
            'nama_perusahaan' => 'PT A',
        ]);

        $this->assertDatabaseHas('keahlians', [
            'pelamar_id' => $pelamar->id,
            'jenis_keahlian' => 'PHP',
        ]);

        $this->assertDatabaseHas('data_anaks', [
            'pelamar_id' => $pelamar->id,
            'nama_anak' => 'Anak 1',
        ]);

        // Check File Storage (Upload & Generated Excel)
        Storage::disk('public')->assertExists($pelamar->foto_path);
        // Note: The generated file path is stored as 'pelamar/filename.xlsx'. 
        // Because we are faking storage, the controller writes to the fake disk.
        Storage::disk('public')->assertExists($pelamar->file_excel_path);
    }
}
