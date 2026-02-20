<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use App\Models\Pelamar;
use App\Models\RiwayatPekerjaan;
use App\Models\Keahlian;
use App\Models\DataAnak;

class PelamarController extends Controller
{
    public function index()
    {
        return view('form');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'tempat_tgl_lahir' => 'required',
            'pendidikan_terakhir' => 'required',
            'alamat_email' => 'required|email',
            'agama' => 'required',
            'nomor_ktp' => 'required',
            'nomor_npwp' => 'required',
            'nomor_rekening_bca' => 'required',
            'nomor_handphone' => 'required',
            'status_perkawinan' => 'required',
            'alamat_domisili' => 'required',
            'rt_rw_dom' => 'required',
            'kelurahan_dom' => 'required',
            'kecamatan_dom' => 'required',
            'kabupaten_kota_dom' => 'required',
            'alamat_ktp' => 'required',
            'rt_rw_ktp' => 'required',
            'kelurahan_ktp' => 'required',
            'kecamatan_ktp' => 'required',
            'kabupaten_kota_ktp' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'nama_perusahaan.*' => 'nullable',
            'bagian_jabatan.*' => 'nullable',
            'mulai_kerja.*' => 'nullable',
            'selesai_kerja.*' => 'nullable',
            'alasan_keluar.*' => 'nullable',
            'jenis_keahlian.*' => 'nullable',
            'penjelasan_skill.*' => 'nullable',
            'nama_ayah' => 'required',
            'nama_ibu_kandung' => 'required',
            'nama_istri_suami' => 'required',
            'nama_anak.*' => 'nullable',
            'nama_keluarga_serumah' => 'required',
            'nomor_hp_keluarga' => 'required',
            'lain_no_1' => 'required',
            'lain_no_2' => 'required',
            'lain_no_3' => 'required',
            'lain_no_4' => 'required',
            'lain_no_5' => 'required',
            'lain_no_6' => 'required',
            'lain_no_7' => 'required',
            'lain_no_8' => 'required',
            'lain_no_9' => 'required',
        ]);


        // 0. MULAI DATABASE TRANSACTION
        DB::beginTransaction();
        try {
            // 0a. Upload Foto untuk Database (Simpan path)
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('fotos', 'public');
            }

            // 0b. Simpan Data Pelamar ke MySQL
            $pelamar = Pelamar::create([
                'nama' => $validated['nama'],
                'tempat_tgl_lahir' => $validated['tempat_tgl_lahir'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
                'alamat_email' => $validated['alamat_email'],
                'agama' => $validated['agama'],
                'nomor_ktp' => $validated['nomor_ktp'],
                'nomor_npwp' => $validated['nomor_npwp'],
                'nomor_rekening_bca' => $validated['nomor_rekening_bca'],
                'nomor_handphone' => $validated['nomor_handphone'],
                'status_perkawinan' => $validated['status_perkawinan'],
                'alamat_domisili' => $validated['alamat_domisili'],
                'rt_rw_dom' => $validated['rt_rw_dom'],
                'kelurahan_dom' => $validated['kelurahan_dom'],
                'kecamatan_dom' => $validated['kecamatan_dom'],
                'kabupaten_kota_dom' => $validated['kabupaten_kota_dom'],
                'alamat_ktp' => $validated['alamat_ktp'],
                'rt_rw_ktp' => $validated['rt_rw_ktp'],
                'kelurahan_ktp' => $validated['kelurahan_ktp'],
                'kecamatan_ktp' => $validated['kecamatan_ktp'],
                'kabupaten_kota_ktp' => $validated['kabupaten_kota_ktp'],
                'foto_path' => $fotoPath,
                'nama_ayah' => $validated['nama_ayah'],
                'nama_ibu_kandung' => $validated['nama_ibu_kandung'],
                'nama_istri_suami' => $validated['nama_istri_suami'],
                'nama_keluarga_serumah' => $validated['nama_keluarga_serumah'],
                'nomor_hp_keluarga' => $validated['nomor_hp_keluarga'],
                // Map Lain-lain
                'lain_no_1' => $validated['lain_no_1'],
                'lain_no_2' => $validated['lain_no_2'],
                'lain_no_3' => $validated['lain_no_3'],
                'lain_no_4' => $validated['lain_no_4'],
                'lain_no_5' => $validated['lain_no_5'],
                'lain_no_6' => $validated['lain_no_6'],
                'lain_no_7' => $validated['lain_no_7'],
                'lain_no_8' => $validated['lain_no_8'],
                'lain_no_9' => $validated['lain_no_9'],
            ]);

            // 0c. Simpan Relasi
            if (isset($validated['nama_perusahaan'])) {
                foreach ($validated['nama_perusahaan'] as $index => $value) {
                    if (empty($value)) continue;
                    $pelamar->riwayatPekerjaans()->create([
                        'nama_perusahaan' => $value,
                        'bagian_jabatan' => $validated['bagian_jabatan'][$index] ?? null,
                        'mulai_kerja' => $validated['mulai_kerja'][$index] ?? null,
                        'selesai_kerja' => $validated['selesai_kerja'][$index] ?? null,
                        'alasan_keluar' => $validated['alasan_keluar'][$index] ?? null,
                    ]);
                }
            }

            if (isset($validated['jenis_keahlian'])) {
                foreach ($validated['jenis_keahlian'] as $index => $value) {
                    if (empty($value)) continue;
                    $pelamar->keahlians()->create([
                        'jenis_keahlian' => $value,
                        'penjelasan_skill' => $validated['penjelasan_skill'][$index] ?? null,
                    ]);
                }
            }

            if (isset($validated['nama_anak'])) {
                foreach ($validated['nama_anak'] as $index => $value) {
                    if (empty($value)) continue;
                    $pelamar->dataAnaks()->create([
                        'nama_anak' => $value,
                    ]);
                }
            }

            // --- LANJUT KE PROSES EXCEL SEPERTI BIASA ---
            
            $templatePath = storage_path('app/templates/Form_Template.xlsx');
    
            if (!file_exists($templatePath)) {
                DB::rollBack(); // Rollback jika template tidak ada
                return redirect()->back()->with('error', 'Template Excel tidak ditemukan.');
            }
    
            // 1. LOAD TEMPLATE
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();
    
            // 2. PROSES FOTO (Untuk Excel)
            if ($request->hasFile('foto')) {
                $photo = $request->file('foto');
                $drawing = new Drawing();
                $drawing->setName('Foto Pelamar');
                $drawing->setPath($photo->getRealPath());
                $drawing->setHeight(240); 
    
                // Centering logic
                list($width, $height) = getimagesize($photo->getRealPath());
                $newWidth = ($width / $height) * 240;
                $boxWidth = 203; 
                $offsetX = ($boxWidth - $newWidth) / 2;
    
                $drawing->setCoordinates('P8');
                $drawing->setOffsetX(max(0, $offsetX));
                $drawing->setOffsetY(10);
                $drawing->setWorksheet($sheet);
            }
    
            // 3. MAPPING DATA STATIS
            $mapping = [
                'nama' => 'I8', 'tempat_tgl_lahir' => 'I9', 'pendidikan_terakhir' => 'I10',
                'alamat_email' => 'I11', 'agama' => 'I12', 'nomor_ktp' => 'I13',
                'nomor_npwp' => 'I14', 'nomor_rekening_bca' => 'I15', 'nomor_handphone' => 'I16',
                'status_perkawinan' => 'I17', 'alamat_domisili' => 'N8', 'rt_rw_dom' => 'N9',
                'kelurahan_dom' => 'N10', 'kecamatan_dom' => 'N11', 'kabupaten_kota_dom' => 'N12',
                'alamat_ktp' => 'N13', 'rt_rw_ktp' => 'N14', 'kelurahan_ktp' => 'N15',
                'kecamatan_ktp' => 'N16', 'kabupaten_kota_ktp' => 'N17', 'nama_ayah' => 'I33',
                'nama_ibu_kandung' => 'I34', 'nama_istri_suami' => 'I35', 'nama_keluarga_serumah' => 'K40',
                'nomor_hp_keluarga' => 'O40', 'lain_no_1' => 'F44', 'lain_no_2' => 'F46',
                'lain_no_3' => 'F48', 'lain_no_4' => 'F50', 'lain_no_5' => 'F52',
                'lain_no_6' => 'F54', 'lain_no_7' => 'F56', 'lain_no_8' => 'F58', 'lain_no_9' => 'F60',
            ];
    
            foreach ($mapping as $field => $cell) {
                $value = $validated[$field];
                if (str_starts_with($field, 'lain_no_')) {
                    $value = ': ' . $value;
                }
                if ($field === 'nomor_hp_keluarga') {
                    $existing = $sheet->getCell($cell)->getValue();
                    $value = $existing . ' ' . $value;
                }
                $sheet->setCellValue($cell, $value);
            }
    
            // 4. DATA DINAMIS (Excel Mapping)
            if (isset($validated['nama_perusahaan'])) {
                foreach ($validated['nama_perusahaan'] as $index => $value) {
                    if ($index > 2 || empty($value)) continue;
                    $row = 22 + $index;
                    $sheet->setCellValue("E{$row}", $value);
                    $sheet->setCellValue("I{$row}", $validated['bagian_jabatan'][$index] ?? '');
                    $sheet->setCellValue("P{$row}", $validated['alasan_keluar'][$index] ?? '');
                    $sheet->setCellValue("K{$row}", $validated['mulai_kerja'][$index] ?? '');
                    $sheet->setCellValue("O{$row}", $validated['selesai_kerja'][$index] ?? '');
                }
            }
    
            if (isset($validated['jenis_keahlian'])) {
                foreach ($validated['jenis_keahlian'] as $index => $value) {
                    if ($index > 2 || empty($value)) continue;
                    $row = 28 + $index;
                    $sheet->setCellValue("E{$row}", $value);
                    $sheet->setCellValue("J{$row}", $validated['penjelasan_skill'][$index] ?? '');
                }
            }
    
            if (isset($validated['nama_anak'])) {
                foreach ($validated['nama_anak'] as $index => $value) {
                    if ($index > 2 || empty($value)) continue;
                    $row = 37 + $index;
                    $existing = $sheet->getCell("E{$row}")->getValue();
                    $sheet->setCellValue("E{$row}", $existing . ' ' . $value);
                }
            }

            // 5. TANGGAL TTD
            Carbon::setLocale('id');
            $sheet->setCellValue('O66', 'Tangerang, ' . Carbon::now()->translatedFormat('d F Y'));
    
            // 6. PROSES SIMPAN FILE
            $safeName = str_replace([' ', '/', '\\'], '_', $validated['nama']);
            $fileName = 'Pelamar_' . $safeName . '_' . time() . '.xlsx';
            $folder = 'pelamar'; 
            $tempPath = tempnam(sys_get_temp_dir(), 'xlsx');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($tempPath);
    
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }
    
            $status = Storage::disk('public')->putFileAs($folder, new File($tempPath), $fileName);
            unlink($tempPath);
    
            if ($status) {
                // UPDATE DB WITH FILE PATH
                $pelamar->update(['file_excel_path' => $folder . '/' . $fileName]);
                
                DB::commit(); // COMMIT TRANSAKSI
                return redirect()->back()->with('success', "Formulir berhasil dikirim! (File: $fileName)");
            }
            
            DB::rollBack();
            return redirect()->back()->with('error', "Gagal memindahkan file ke storage.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }
}