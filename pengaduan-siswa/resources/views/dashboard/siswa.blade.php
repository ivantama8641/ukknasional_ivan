@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold" style="color: #1e293b;">Dashboard Siswa</h2>
            <p class="text-muted">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-bullhorn fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold opacity-75">Total Pengaduan</h6>
                        <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold opacity-75">Sedang Diproses</h6>
                        <h2 class="mb-0 fw-bold">{{ $stats['menunggu'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold opacity-75">Selesai</h6>
                        <h2 class="mb-0 fw-bold">{{ $stats['selesai'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Complaints Table -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">Pengaduan Saya</h5>
            <a href="{{ route('siswa.complaints.create') }}" class="btn btn-primary btn-sm fw-semibold rounded-pill px-3 shadow-sm">
                <i class="fas fa-plus me-1"></i> Buat Pengaduan
            </a>
        </div>
        <div class="card-body p-4">
            @if($myComplaints->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myComplaints as $complaint)
                                <tr>
                                    <td class="text-muted">{{ $complaint->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $complaint->category->color }}; color: white;">
                                            <i class="{{ $complaint->category->icon }} me-1"></i> {{ $complaint->category->name }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">{{ \Illuminate\Support\Str::limit($complaint->title, 40) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $complaint->status_badge }} px-2 py-1 rounded-pill">
                                            <i class="{{ $complaint->status_icon }} me-1"></i> {{ $complaint->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('siswa.complaints.show', $complaint->id) }}" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Lihat Detail">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted fw-semibold">Belum Ada Pengaduan</h5>
                    <p class="text-muted small">Anda belum membuat pengaduan sama sekali.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
