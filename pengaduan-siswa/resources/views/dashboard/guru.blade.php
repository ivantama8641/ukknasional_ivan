@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold" style="color: #1e293b;">Dashboard Guru / Wali Kelas</h2>
            <p class="text-muted">Pantau pengaduan siswa dan tindak lanjuti laporan yang masuk.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 bg-primary text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-bullhorn fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold opacity-75">Total Pengaduan (Sekolah)</h6>
                        <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 bg-success text-white" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-hands-helping fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold opacity-75">Telah Anda Tangani</h6>
                        <h2 class="mb-0 fw-bold">{{ $stats['ditangani'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Assignments or Reports -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">Daftar Pengaduan Siswa</h5>
            <a href="{{ route('guru.complaints.index') }}" class="btn btn-primary btn-sm rounded-pill fw-semibold px-3">Filter Pengaduan <i class="fas fa-filter px-1"></i></a>
        </div>
        <div class="card-body p-4">
            @if($recentComplaints->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Pengirim</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentComplaints as $complaint)
                                <tr>
                                    <td class="text-muted">{{ $complaint->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $complaint->user->name }}</div>
                                        <div class="small text-muted">{{ $complaint->user->kelas }}</div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $complaint->category->color }}20; color: {{ $complaint->category->color }}; border: 1px solid {{ $complaint->category->color }}50;">
                                            <i class="{{ $complaint->category->icon }} me-1"></i> {{ $complaint->category->name }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ \Illuminate\Support\Str::limit($complaint->title, 40) }}</td>
                                    <td>
                                        <span class="badge border border-{{ $complaint->status_badge }} text-{{ $complaint->status_badge }} rounded-pill bg-white px-2 py-1">
                                            <i class="{{ $complaint->status_icon }} me-1"></i> {{ $complaint->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('guru.complaints.show', $complaint->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold text-primary">Tindak <i class="fas fa-arrow-right ms-1"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted fw-semibold">Belum Ada Pengaduan</h5>
                    <p class="text-muted small">Mungkin sekolah sedang dalam keadaan damai dan sejahtera.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
