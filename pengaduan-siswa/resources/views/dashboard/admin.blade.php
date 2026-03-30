@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold" style="color: #1e293b;">Dashboard Admin</h2>
                <p class="text-muted mb-0">Overview sistem pengaduan hari ini</p>
            </div>
            <div>
                <span class="badge bg-light text-primary fs-6 py-2 px-3 border"><i class="fas fa-calendar-alt me-2"></i> {{ date('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 bg-white border-start border-4 border-primary">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Total Pengaduan</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-bullhorn fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 bg-white border-start border-4 border-warning">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Menunggu</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['menunggu'] }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 bg-white border-start border-4 border-info">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Diproses</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['diproses'] }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-spinner fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 bg-white border-start border-4 border-success">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Total Pengguna</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['users'] }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Pengaduan Terbaru Masuk</h5>
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold px-3">Lihat Semua</a>
                </div>
                <div class="card-body p-4">
                    @if($recentComplaints->count() > 0)
                        <div class="list-group list-group-flush border-bottom scrollarea">
                            @foreach($recentComplaints as $complaint)
                            <div class="list-group-item list-group-item-action py-3 lh-sm">
                                <div class="d-flex w-100 align-items-center justify-content-between mb-1">
                                    <strong class="mb-1 text-dark">{{ \Illuminate\Support\Str::limit($complaint->title, 50) }}</strong>
                                    <small class="text-muted">{{ $complaint->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="col-10 mb-2 small text-muted">
                                    Oleh: <strong>{{ $complaint->user->name }}</strong> ({{ $complaint->user->kelas }})
                                </div>
                                <div>
                                    <span class="badge border border-{{ $complaint->status_badge }} text-{{ $complaint->status_badge }} rounded-pill bg-white px-2 py-1">
                                        <i class="{{ $complaint->status_icon }} me-1"></i> {{ $complaint->status_label }}
                                    </span>
                                    <span class="badge ms-1" style="background-color: {{ $complaint->category->color }}20; color: {{ $complaint->category->color }};">
                                        <i class="{{ $complaint->category->icon }} me-1"></i> {{ $complaint->category->name }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open text-muted mb-2 opacity-50 fa-2x"></i>
                            <p class="text-muted fw-semibold">Belum ada pengaduan terbaru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 text-center">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/web-development-3454628-2918517.png" alt="Admin Illustration" class="img-fluid mb-4" style="max-height: 180px;">
                    <h5 class="fw-bold">Manajemen Pusat</h5>
                    <p class="text-muted small mb-4">Kelola semua data pengguna, kategori, dan laporan masuk melalui menu navigasi di atas.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-primary rounded-3 fw-semibold"><i class="fas fa-tags me-2"></i>Kelola Kategori</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold"><i class="fas fa-users me-2"></i>Kelola Pengguna</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
