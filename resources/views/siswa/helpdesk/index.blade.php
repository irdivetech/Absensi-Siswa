@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Helpdesk /</span> Tiket Saya</h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Laporan Kendala</h5>
            <a href="{{ route('siswa.helpdesk.create') }}" class="btn btn-primary"><i class="bx bx-plus me-1"></i> Buat Tiket Baru</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID Tiket</th>
                        <th>Subjek</th>
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
                        <td>{{ Str::limit($ticket->subject, 30) }}</td>
                        <td><span class="badge bg-label-info">{{ $ticket->category }}</span></td>
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
                            <a href="{{ route('siswa.helpdesk.show', $ticket->id) }}" class="btn btn-sm btn-info">Lihat Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Anda belum pernah membuat tiket bantuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
