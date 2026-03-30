@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-dark">Detail Pengaduan #{{ str_pad($complaint->id, 5, '0', STR_PAD_LEFT) }}</h3>
                <p class="text-muted mb-0">Dibuat pada {{ $complaint->created_at->format('d M Y - H:i') }}</p>
            </div>
            <a href="{{ route('siswa.dashboard') }}" class="btn btn-light shadow-sm border rounded-pill fw-semibold px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Complaint Info -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <span class="badge border border-{{ $complaint->status_badge }} text-{{ $complaint->status_badge }} rounded-pill bg-white px-3 py-2 fs-6 shadow-sm me-2">
                            <i class="{{ $complaint->status_icon }} me-1"></i> {{ $complaint->status_label }}
                        </span>
                        <span class="badge" style="background-color: {{ $complaint->category->color }}20; color: {{ $complaint->category->color }}; border: 1px solid {{ $complaint->category->color }}50; padding: 0.5rem 1rem; border-radius: 50rem;">
                            <i class="{{ $complaint->category->icon }} me-1"></i> {{ $complaint->category->name }}
                        </span>
                    </div>

                    <h4 class="fw-bold mb-4">{{ $complaint->title }}</h4>

                    <div class="bg-light p-4 rounded-3 text-dark mb-4" style="font-size: 1.05rem; white-space: pre-wrap;">{{ $complaint->description }}</div>

                    @if($complaint->attachment)
                        <h6 class="fw-bold mb-3"><i class="fas fa-paperclip me-2 text-muted"></i>Lampiran Bukti</h6>
                        @if(Str::endsWith(strtolower($complaint->attachment), ['.jpg', '.jpeg', '.png']))
                            <img src="{{ Storage::url($complaint->attachment) }}" class="img-fluid rounded-3 border mb-4" alt="Lampiran" style="max-height: 400px; object-fit: contain;">
                        @else
                            <a href="{{ Storage::url($complaint->attachment) }}" target="_blank" class="btn btn-outline-primary rounded-pill fw-semibold mb-4 bg-primary bg-opacity-10 shadow-sm border-0">
                                <i class="fas fa-file-pdf me-2"></i>  Lihat File Terlampir
                            </a>
                        @endif
                    @endif

                    <!-- Penanganan -->
                    <hr class="text-muted opacity-25">
                    <div class="d-flex align-items-center mt-4">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Ditangani Oleh:</p>
                            @if($complaint->handler)
                                <h6 class="fw-bold mb-0 text-dark">{{ $complaint->handler->name }} <span class="badge bg-secondary ms-1 fw-normal">{{ ucfirst($complaint->handler->role) }}</span></h6>
                            @else
                                <h6 class="fw-semibold text-muted mb-0 fst-italic">Belum ada penanggung jawab (Menunggu)</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responses Section -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-comments me-2 text-primary"></i>Riwayat Tindak Lanjut</h5>
                </div>
                <div class="card-body p-4">
                    @forelse($complaint->responses as $response)
                        <div class="d-flex mb-4 p-3 rounded-4 shadow-sm {{ $response->user->role === 'siswa' ? 'bg-primary bg-opacity-10 ms-md-5 border-start border-primary border-4' : 'bg-light me-md-5 border-start border-secondary border-4' }}">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas {{ $response->user->role === 'siswa' ? 'fa-user text-primary' : 'fa-user-tie text-secondary' }} fa-2x mt-1"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">{{ $response->user->name }} <span class="badge bg-{{ $response->user->role === 'siswa' ? 'primary' : 'secondary' }} ms-1 fw-normal rounded-pill" style="font-size: 0.70rem;">{{ ucfirst($response->user->role) }}</span></h6>
                                <p class="text-muted small mb-2"><i class="fas fa-clock me-1"></i> {{ $response->created_at->format('d/m/Y - H:i') }}</p>
                                <div class="text-dark">{{ $response->message }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-envelope-open-text text-muted mb-3 opacity-25 fa-3x"></i>
                            <p class="text-muted fw-semibold mb-0">Belum ada respon atau tanggapan untuk pengaduan ini.</p>
                        </div>
                    @endforelse

                    @if(in_array($complaint->status, ['diproses', 'menunggu']))
                        <div class="mt-4 pt-3 border-top">
                            <form action="{{ route('siswa.complaints.response', $complaint->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">Tambahkan Komentar Tambahan</label>
                                    <textarea name="message" class="form-control rounded-3" rows="3" placeholder="Tulis komentar balasan Anda..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm"><i class="fas fa-paper-plane me-2"></i>Kirim Respon</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-secondary border-0 mt-4 text-center rounded-3">
                            <i class="fas fa-lock me-2 text-muted"></i> Pengaduan ini telah <strong>{{ $complaint->status_label }}</strong>. Komentar ditutup.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Timeline/Status -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 2rem;">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Track Status</h5>
                </div>
                <div class="card-body p-4">
                    <div class="position-relative ms-3 border-start border-2 border-primary pb-4 ps-4">
                        <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                            <i class="fas fa-check small"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Pengaduan Dikirim</h6>
                        <p class="text-muted small mb-0">{{ $complaint->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div class="position-relative ms-3 border-start border-2 {{ in_array($complaint->status, ['diproses', 'selesai', 'ditolak']) ? 'border-primary' : 'border-light' }} pb-4 ps-4">
                        <div class="position-absolute top-0 start-0 translate-middle rounded-circle {{ in_array($complaint->status, ['diproses', 'selesai', 'ditolak']) ? 'bg-primary text-white' : 'bg-light text-muted' }} d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                            <i class="fas {{ in_array($complaint->status, ['diproses', 'selesai', 'ditolak']) ? 'fa-check' : 'fa-spinner' }} small"></i>
                        </div>
                        <h6 class="fw-bold {{ in_array($complaint->status, ['diproses', 'selesai', 'ditolak']) ? 'text-dark' : 'text-muted' }} mb-1">Sedang Diproses</h6>
                        @if($complaint->status === 'diproses' || $complaint->status === 'selesai' || $complaint->status === 'ditolak')
                            <p class="text-muted small mb-0">Pengaduan sedang ditinjau.</p>
                        @endif
                    </div>

                    <div class="position-relative ms-3 ps-4">
                        <div class="position-absolute top-0 start-0 translate-middle rounded-circle {{ in_array($complaint->status, ['selesai', 'ditolak']) ? 'bg-primary text-white' : 'bg-light text-muted' }} d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                            <i class="fas {{ $complaint->status == 'selesai' ? 'fa-check-double' : ($complaint->status == 'ditolak' ? 'fa-times' : 'fa-flag-checkered') }} small"></i>
                        </div>
                        <h6 class="fw-bold {{ in_array($complaint->status, ['selesai', 'ditolak']) ? 'text-dark' : 'text-muted' }} mb-1">
                            @if($complaint->status === 'ditolak') Ditolak @else Selesai @endif
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
