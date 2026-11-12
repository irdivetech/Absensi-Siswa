@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Helpdesk /</span> Buat Tiket Baru
    </h4>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Pengaduan Kendala</h5>
                </div>
                <div class="card-body">
                    <form id="create-ticket-form" action="{{ route('siswa.helpdesk.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="category">Kategori Kendala</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="QR Error">Gagal Scan QR Code</option>
                                <option value="Face Recognition Error">Face Recognition Gagal</option>
                                <option value="Location Error">Lokasi (GPS) Tidak Terdeteksi</option>
                                <option value="Lupa Absen">Lupa Absen</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="subject">Subjek/Judul</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Contoh: Kamera gelap saat scan wajah" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Deskripsi Detail</label>
                            <textarea id="description" name="description" class="form-control" placeholder="Jelaskan secara detail kendala yang Anda alami..." rows="4" required></textarea>
                        </div>

                        <!-- Area Peringatan Duplikasi -->
                        <div id="duplicate-warning" class="alert alert-warning d-none" role="alert">
                            <h6 class="alert-heading fw-bold mb-1"><i class="bx bx-error-circle"></i> Tunggu! Apakah ini kendala yang sedang Anda alami?</h6>
                            <p class="mb-2 text-sm">Sistem mendeteksi Anda memiliki laporan serupa yang belum ditutup:</p>
                            <ul id="duplicate-list" class="mb-0 text-sm"></ul>
                            <hr>
                            <p class="mb-0 text-sm">Jika iya, mohon tidak membuat tiket baru untuk mempercepat proses. Silakan pantau tiket Anda yang sudah ada.</p>
                        </div>

                        <button type="submit" class="btn btn-primary" id="btn-submit">Kirim Laporan</button>
                        <a href="{{ route('siswa.helpdesk.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Panduan Helpdesk</h5>
                    <p class="card-text">
                        1. Pastikan Anda memilih kategori yang paling sesuai.<br>
                        2. Tulis deskripsi dengan jelas dan singkat.<br>
                        3. Jangan membuat laporan ganda untuk masalah yang sama.<br>
                        4. Pantau status tiket Anda secara berkala.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectInput = document.getElementById('subject');
    const descInput = document.getElementById('description');
    const warningBox = document.getElementById('duplicate-warning');
    const duplicateList = document.getElementById('duplicate-list');
    let timeoutId;

    function checkDuplication() {
        clearTimeout(timeoutId);
        
        const query = subjectInput.value + ' ' + descInput.value;
        if(query.trim().length < 5) {
            warningBox.classList.add('d-none');
            return;
        }

        timeoutId = setTimeout(() => {
            fetch(`/siswa/helpdesk/check-duplicate?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if(data.length > 0) {
                        duplicateList.innerHTML = '';
                        data.forEach(ticket => {
                            const li = document.createElement('li');
                            li.innerHTML = `<a href="/siswa/helpdesk/${ticket.id}" target="_blank" class="text-dark fw-bold">#${ticket.id} - ${ticket.subject}</a> (Status: ${ticket.status})`;
                            duplicateList.appendChild(li);
                        });
                        warningBox.classList.remove('d-none');
                    } else {
                        warningBox.classList.add('d-none');
                    }
                });
        }, 500); // 500ms debounce
    }

    subjectInput.addEventListener('keyup', checkDuplication);
    descInput.addEventListener('keyup', checkDuplication);

    // AJAX Form Submit
    const form = document.getElementById('create-ticket-form');
    const submitBtn = document.getElementById('btn-submit');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
        submitBtn.disabled = true;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tampilkan notifikasi toast sederhana (tanpa sweetalert)
                const toastHTML = `
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
                    <div id="liveToast" class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class='bx bx-check-circle me-2'></i> ${data.message}
                            </div>
                        </div>
                    </div>
                </div>`;
                document.body.insertAdjacentHTML('beforeend', toastHTML);

                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1500);
            } else {
                alert('Terjadi kesalahan, silakan coba lagi.');
                submitBtn.innerHTML = 'Kirim Laporan';
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi.');
            submitBtn.innerHTML = 'Kirim Laporan';
            submitBtn.disabled = false;
        });
    });
});
</script>
@endsection
