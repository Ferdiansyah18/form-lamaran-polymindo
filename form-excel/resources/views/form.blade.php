<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Data Pelamar - Multi Step</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .step-container { transition: all 0.3s ease-in-out; }
        .progress { height: 10px; border-radius: 5px; }
        .card-header { border-bottom: none; }
        .form-label { font-weight: 500; color: #444; }
        .d-none { display: none; } /* Memastikan class d-none bekerja untuk menyembunyikan step */
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white pt-4 px-4">
            <h4 class="mb-3">Form Data Pelamar</h4>
            <div class="progress mb-3" style="background-color: rgba(255,255,255,0.2);">
                <div id="progress-bar" class="progress-bar bg-warning" role="progressbar" style="width: 20%;"></div>
            </div>
            <p id="step-indicator" class="small">Halaman 1 dari 5: Data Pribadi</p>
        </div>

        <div class="card-body p-4">

            {{-- ERROR VALIDATION & SESSION MESSAGES --}}
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm border-0">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Fallback Alert jika SweetAlert gagal load --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                    <span class="me-2">✅</span>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('generate') }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf

                {{-- ================= STEP 1: DATA PRIBADI ================= --}}
                <div class="step-container" id="step-1">
                    <h5 class="mb-3 border-bottom pb-2">Data Pribadi</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat & Tanggal Lahir</label>
                            <input type="text" name="tempat_tgl_lahir" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <select name="pendidikan_terakhir" class="form-select" required>
                                <option value="">-- Pilih Pendidikan --</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA/SMK">SMA / SMK / Sederajat</option>
                                <option value="D1/D2/D3">D1 / D2 / D3</option>
                                <option value="S1/D4">S1 / D4</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="alamat_email" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Agama</label>
                            <select name="agama" class="form-select" required>
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor KTP</label>
                            <input type="number" name="nomor_ktp" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor NPWP</label>
                            <input type="number" name="nomor_npwp" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Rekening BCA</label>
                            <input type="number" name="nomor_rekening_bca" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Handphone</label>
                            <input type="number" name="nomor_handphone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Perkawinan</label>
                            <select name="status_perkawinan" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option>Belum Menikah</option>
                                <option>Menikah</option>
                                <option>Cerai</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Foto</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-primary btn-next px-4">Lanjut</button>
                    </div>
                </div>

                {{-- ================= STEP 2: ALAMAT ================= --}}
                <div class="step-container d-none" id="step-2">
                    <h5 class="mb-3 border-bottom pb-2">Alamat Domisili & KTP</h5>
                    <div class="row g-3">
                        <h6 class="text-muted">Alamat Domisili</h6>
                        <div class="col-12">
                            <textarea name="alamat_domisili" class="form-control" placeholder="Alamat lengkap" required></textarea>
                        </div>
                        <div class="col-md-6"><input type="text" name="rt_rw_dom" class="form-control" placeholder="RT / RW" required></div>
                        <div class="col-md-6"><input type="text" name="kelurahan_dom" class="form-control" placeholder="Kelurahan" required></div>
                        <div class="col-md-6"><input type="text" name="kecamatan_dom" class="form-control" placeholder="Kecamatan" required></div>
                        <div class="col-md-6"><input type="text" name="kabupaten_kota_dom" class="form-control" placeholder="Kab/Kota" required></div>

                        <h6 class="text-muted mt-4">Alamat Sesuai KTP</h6>
                        <div class="col-12">
                            <textarea name="alamat_ktp" class="form-control" placeholder="Alamat lengkap KTP" required></textarea>
                        </div>
                        <div class="col-md-6"><input type="text" name="rt_rw_ktp" class="form-control" placeholder="RT / RW" required></div>
                        <div class="col-md-6"><input type="text" name="kelurahan_ktp" class="form-control" placeholder="Kelurahan" required></div>
                        <div class="col-md-6"><input type="text" name="kecamatan_ktp" class="form-control" placeholder="Kecamatan" required></div>
                        <div class="col-md-6"><input type="text" name="kabupaten_kota_ktp" class="form-control" placeholder="Kab/Kota" required></div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary btn-prev">Kembali</button>
                        <button type="button" class="btn btn-primary btn-next px-4">Lanjut</button>
                    </div>
                </div>

                {{-- ================= STEP 3: RIWAYAT PEKERJAAN & KEAHLIAN ================= --}}
                <div class="step-container d-none" id="step-3">
                    <h5 class="mb-3 border-bottom pb-2">Riwayat Pekerjaan & Keahlian</h5>
                    
                    <div id="pekerjaan-wrapper">
                        <div class="row g-3 pekerjaan-item mb-3 border rounded p-2 bg-white mx-0">
                            <div class="col-md-4"><input type="text" name="nama_perusahaan[]" class="form-control" placeholder="Nama Perusahaan"></div>
                            <div class="col-md-4"><input type="text" name="bagian_jabatan[]" class="form-control" placeholder="Jabatan"></div>
                            <div class="col-md-4"><input type="text" name="alasan_keluar[]" class="form-control" placeholder="Alasan Keluar"></div>
                            <div class="col-md-3"><label class="small">Mulai</label><input type="date" name="mulai_kerja[]" class="form-control"></div>
                            <div class="col-md-3"><label class="small">Selesai</label><input type="date" name="selesai_kerja[]" class="form-control"></div>
                        </div>
                    </div>
                    <button type="button" id="tambah-pekerjaan" class="btn btn-sm btn-outline-primary mb-4">+ Tambah Pengalaman</button>

                    <h5 class="mb-3 border-bottom pb-2">Keahlian</h5>
                    <div id="skill-wrapper">
                        <div class="row g-2 skill-item mb-2">
                            <div class="col-md-6"><input type="text" name="jenis_keahlian[]" class="form-control" placeholder="Jenis Keahlian"></div>
                            <div class="col-md-6"><input type="text" name="penjelasan_skill[]" class="form-control" placeholder="Penjelasan"></div>
                        </div>
                    </div>
                    <button type="button" id="tambah-skill" class="btn btn-sm btn-outline-primary mb-2">+ Tambah Skill</button>

                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary btn-prev">Kembali</button>
                        <button type="button" class="btn btn-primary btn-next px-4">Lanjut</button>
                    </div>
                </div>

                {{-- ================= STEP 4: DATA KELUARGA ================= --}}
                <div class="step-container d-none" id="step-4">
                    <h5 class="mb-3 border-bottom pb-2">Data Keluarga</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" placeholder="Nama Ayah" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Ibu Kandung</label>
                            <input type="text" name="nama_ibu_kandung" class="form-control" placeholder="Nama Ibu Kandung" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Istri / Suami</label>
                            <input type="text" name="nama_istri_suami" class="form-control" placeholder="Isi '-' jika belum ada" required>
                        </div>

                        <div id="anak-wrapper" class="col-12">
                            <div class="row g-2 anak-item mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Anak</label>
                                    <input type="text" name="nama_anak[]" class="form-control" placeholder="Nama Anak">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="button" id="tambah-anak" class="btn btn-sm btn-outline-primary mb-3">+ Tambah Anak</button>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Keluarga Serumah</label>
                            <input type="text" name="nama_keluarga_serumah" class="form-control" placeholder="Nama Keluarga Serumah" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No HP Keluarga</label>
                            <input type="number" name="nomor_hp_keluarga" class="form-control" placeholder="No HP Keluarga" required>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary btn-prev">Kembali</button>
                        <button type="button" class="btn btn-primary btn-next px-4">Lanjut</button>
                    </div>
                </div>

                {{-- ================= STEP 5: LAIN-LAIN ================= --}}
                <div class="step-container d-none" id="step-5">
                    <h5 class="mb-3 border-bottom pb-2">Informasi Tambahan</h5>
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label small">1. Apakah Saudara pernah mengikuti kursus/pelatihan? Jika ya tulis apa, berapa lama?</label>
                            <textarea name="lain_no_1" class="form-control" rows="1" placeholder="Contoh: Kursus Excel, 1 Bulan" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">2. Menurut saudara berdasarkan keahlian saudara seberapa besar gaji yang layak untuk dibayarkan?</label>
                            <input type="text" name="lain_no_2" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">3. Bersediakan Saudara bekerja Shift I, II, III ? Jika ya mengapa ?</label>
                            <input type="text" name="lain_no_3" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">4. Jika diterima, kapan Saudara siap mulai kerja ?</label>
                            <input type="text" name="lain_no_4" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">5. Apa hobby Saudara yang saudara jalani sampai hari ini? </label>
                            <input type="text" name="lain_no_5" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">6. Seberapa sering Saudara beribadah menurut ajaran agama? Dan Kenapa?</label>
                            <textarea name="lain_no_6" class="form-control" rows="1" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">7. Atasan ideal menurut saudara seperti apa dan bagaimana sifatnya ?</label>
                            <textarea name="lain_no_7" class="form-control" rows="1" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">8. Perusahaan yang ideal menurut anda bagaimana?</label>
                            <textarea name="lain_no_8" class="form-control" rows="1" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">9. Tuliskan akun media sosial yang anda miliki!</label>
                            <input type="text" name="lain_no_9" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary btn-prev">Kembali</button>
                        <button type="submit" class="btn btn-success px-5 shadow-sm">Simpan & Generate Excel</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const steps = document.querySelectorAll(".step-container");
    const nextBtns = document.querySelectorAll(".btn-next");
    const prevBtns = document.querySelectorAll(".btn-prev");
    const progressBar = document.getElementById("progress-bar");
    const stepIndicator = document.getElementById("step-indicator");
    
    let currentStep = 0;

    const stepNames = [
        "Data Pribadi",
        "Alamat Domisili & KTP",
        "Pekerjaan & Keahlian",
        "Data Keluarga",
        "Lain-Lain"
    ];

    function updateStepDisplay() {
        steps.forEach((step, index) => {
            step.classList.toggle("d-none", index !== currentStep);
        });
        
        const progressPercent = ((currentStep + 1) / steps.length) * 100;
        progressBar.style.width = progressPercent + "%";
        
        stepIndicator.innerText = `Halaman ${currentStep + 1} dari ${steps.length}: ${stepNames[currentStep]}`;
        
        window.scrollTo(0, 0);
    }

    nextBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            const currentInputs = steps[currentStep].querySelectorAll("input[required], select[required], textarea[required]");
            let isValid = true;

            currentInputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                }
            });

            if (isValid && currentStep < steps.length - 1) {
                currentStep++;
                updateStepDisplay();
            }
        });
    });

    prevBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                updateStepDisplay();
            }
        });
    });

    function setupTambah(wrapperId, buttonId, itemClass) {
        const wrapper = document.getElementById(wrapperId);
        const button = document.getElementById(buttonId);

        button.addEventListener("click", function() {
            const items = wrapper.getElementsByClassName(itemClass);
            if (items.length < 3) {
                const clone = items[0].cloneNode(true);
                clone.querySelectorAll("input").forEach(input => input.value = "");
                wrapper.appendChild(clone);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Maksimal',
                    text: 'Maksimal 3 data yang dapat ditambahkan.',
                    confirmButtonColor: '#0d6efd'
                });
            }
        });
    }

    setupTambah("pekerjaan-wrapper", "tambah-pekerjaan", "pekerjaan-item");
    setupTambah("skill-wrapper", "tambah-skill", "skill-item");
    setupTambah("anak-wrapper", "tambah-anak", "anak-item");

    // ================= LOGIKA KONFIRMASI BERHASIL =================
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil Terkirim!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Selesai'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Gagal!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    @endif
});
</script>

</body>
</html>