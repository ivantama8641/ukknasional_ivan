@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0 text-dark">Pusat Notifikasi</h3>
            <p class="text-muted mb-0">Informasi terbaru terkait aktivitas akun Anda</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary rounded-pill fw-semibold px-4 shadow-sm me-2">
                        <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif
            <a href="{{ url()->previous() }}" class="btn btn-light shadow-sm border rounded-pill fw-semibold px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="list-group list-group-flush">
            @forelse($notifications as $noti)
                <div class="list-group-item p-4 {{ $noti->is_read ? 'bg-white' : 'bg-primary bg-opacity-10 border-start border-primary border-4' }}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-{{ $noti->type ?? 'info' }} bg-opacity-10 text-{{ $noti->type ?? 'info' }} d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                                <i class="fas {{ $noti->type == 'success' ? 'fa-check' : ($noti->type == 'danger' ? 'fa-exclamation-circle' : 'fa-info') }}"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 {{ $noti->is_read ? 'text-dark' : 'text-primary' }}">{{ $noti->title }}</h6>
                                <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $noti->created_at->format('d M Y, H:i') }} ({{ $noti->created_at->diffForHumans() }})</small>
                            </div>
                        </div>
                        @if(!$noti->is_read)
                            <a href="{{ route('notifications.read', $noti->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm fw-bold">Buka & Baca</a>
                        @elseif($noti->complaint_id)
                            <a href="{{ route(auth()->user()->role . '.complaints.show', $noti->complaint_id) }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold">Lihat Detail</a>
                        @endif
                    </div>
                    <div class="text-muted ms-5 ps-2 pt-1">{{ $noti->message }}</div>
                </div>
            @empty
                <div class="py-5 text-center px-4">
                    <i class="fas fa-bell-slash fa-4x text-muted mb-4 opacity-25"></i>
                    <h5 class="fw-bold text-muted">Belum Ada Notifikasi</h5>
                    <p class="text-muted">Semua pemberitahuan aktivitas aplikasi akan muncul di halaman ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
