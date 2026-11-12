@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Helpdesk /</span> Antrean Tiket</h4>

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tiket Kendala</h5>
            <div>
                <a href="{{ route('admin.helpdesk.index', ['status' => 'open']) }}" class="btn btn-sm btn-outline-warning">Open</a>
                <a href="{{ route('admin.helpdesk.index', ['status' => 'in_progress']) }}" class="btn btn-sm btn-outline-primary">In Progress</a>
                <a href="{{ route('admin.helpdesk.index', ['status' => 'closed']) }}" class="btn btn-sm btn-outline-success">Closed</a>
                <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID Tiket</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($tickets as $ticket)
                    <tr>
                        <td><strong>#{{ $ticket->id }}</strong></td>
                        <td>{{ $ticket->reporter ? $ticket->reporter->name : 'Unknown' }}</td>
                        <td>
                            <span class="badge bg-label-info">{{ $ticket->category }}</span>
                            @if($ticket->needs_admin)
                                <span class="badge bg-danger ms-1 mt-1 pulse-animation" style="font-size: 0.65rem;">🚨 BUTUH ADMIN</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->status == 'open')
                                <span class="badge bg-label-warning">Open</span>
                            @elseif($ticket->status == 'in_progress')
                                <span class="badge bg-label-primary">Diproses</span>
                            @else
                                <span class="badge bg-label-success">Selesai</span>
                            @endif
                        </td>
                        <td>{{ $ticket->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.helpdesk.show', $ticket->id) }}" class="btn btn-sm btn-primary">Buka</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada tiket yang sesuai kriteria.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 62, 29, 0.7); }
        70% { box-shadow: 0 0 0 6px rgba(255, 62, 29, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 62, 29, 0); }
    }
    .pulse-animation {
        animation: pulse 2s infinite;
    }
</style>

<script>
    // Auto reload data table setiap 3 detik
    setInterval(() => {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTbody = doc.querySelector('.table-border-bottom-0');
            if (newTbody) {
                document.querySelector('.table-border-bottom-0').innerHTML = newTbody.innerHTML;
            }
        })
        .catch(err => console.error('Gagal memuat ulang antrean:', err));
    }, 3000);
</script>
@endsection
