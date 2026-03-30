@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0 text-dark">Data Semua Pengaduan</h3>
            <p class="text-muted mb-0">Kelola dan verifikasi laporan yang masuk</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light shadow-sm border rounded-pill fw-semibold px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Pengirim</th>
                            <th>Kategori</th>
                            <th>Judul Pengaduan</th>
                            <th>Status & Prioritas</th>
                            <th>Penanganan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr>
                                <td class="text-muted fw-semibold">#{{ str_pad($complaint->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $complaint->user->name }}</div>
                                    <div class="small text-muted">{{ $complaint->user->kelas }}</div>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: {{ $complaint->category->color }}20; color: {{ $complaint->category->color }}; border: 1px solid {{ $complaint->category->color }}50;">
                                        <i class="{{ $complaint->category->icon }} me-1"></i> {{ $complaint->category->name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-dark fw-semibold">{{ \Illuminate\Support\Str::limit($complaint->title, 35) }}</div>
                                    <div class="small text-muted">{{ $complaint->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <span class="badge border border-{{ $complaint->status_badge }} text-{{ $complaint->status_badge }} px-2 py-1 rounded-pill mb-1 d-inline-block">
                                        <i class="{{ $complaint->status_icon }} me-1"></i> {{ $complaint->status_label }}
                                    </span>
                                    <br>
                                    <span class="badge bg-{{ $complaint->priority_badge }}  px-2 py-1 rounded-pill">
                                        {{ $complaint->priority_label }}
                                    </span>
                                </td>
                                <td>
                                    @if($complaint->handler)
                                        <span class="text-dark fw-semibold small"><i class="fas fa-user-tie text-muted me-1"></i> {{ $complaint->handler->name }}</span>
                                    @else
                                        <span class="badge bg-light text-muted border"><i class="fas fa-hourglass-half me-1"></i> Belum Ditunjuk</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm fw-semibold">
                                        Kelola <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                                    <h5 class="text-muted fw-semibold">Belum Ada Pengaduan</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 d-flex justify-content-end">
                {{ $complaints->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
