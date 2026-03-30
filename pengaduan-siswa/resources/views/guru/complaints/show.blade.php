@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold mb-0 text-dark">Tindak Lanjut Pengaduan #{{ str_pad($complaint->id, 4, '0', STR_PAD_LEFT) }}</h3>
            <p class="text-muted mb-0">Ditugaskan kepada Anda oleh pihak sekolah</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('guru.complaints.index') }}" class="btn btn-light shadow-sm border rounded-pill fw-semibold px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0 rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Main Column: Complaint Info & Responses -->
        <div class="col-lg-8">
            <!-- Detail Aduan -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-dark">Detail Laporan Siswa</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 d-flex align-items-center">
                         <span class="badge" style="background-color: {{ $complaint->category->color }}20; color: {{ $complaint->category->color }}; border: 1px solid {{ $complaint->category->color }}50; padding: 0.5rem 1rem; border-radius: 50rem;">
                            <i class="{{ $complaint->category->icon }} me-1"></i> {{ $complaint->category->name }}
                        </span>
                        <span class="badge ms-2 border border-{{ $complaint->status_badge }} text-{{ $complaint->status_badge }} rounded-pill bg-white px-3 py-2 shadow-sm">
                            <i class="{{ $complaint->status_icon }} me-1"></i> Status Saat Ini: {{ $complaint->status_label }}
                        </span>
                    </div>

                    <h4 class="fw-bold mb-3">{{ $complaint->title }}</h4>
                    <div class="bg-light p-4 rounded-3 text-dark mb-4" style="white-space: pre-wrap; font-size: 1.05rem;">{{ $complaint->description }}</div>

                    <!-- Profil Siswa (Ringkas) -->
                    <div class="d-flex border p-3 rounded-4 bg-white mt-4 align-items-center">
                        <div class="me-3">
                            <i class="fas fa-user-graduate fa-3x text-primary opacity-75"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">{{ $complaint->user->name }}</h6>
                            <p class="mb-0 text-muted small"><i class="fas fa-graduation-cap me-1"></i> {{ $complaint->user->kelas }} {{ $complaint->user->jurusan }} &bull; <i class="fas fa-phone-alt ms-2 me-1"></i> {{ $complaint->user->phone ?? 'Tidak ada kontak' }}</p>
                        </div>
                    </div>

                    @if($complaint->attachment)
                        <hr class="my-4 text-muted opacity-25">
                        <h6 class="fw-bold mb-3"><i class="fas fa-paperclip text-muted me-2"></i>Lampiran Siswa</h6>
                        @if(Str::endsWith(strtolower($complaint->attachment), ['.jpg', '.jpeg', '.png']))
                            <img src="{{ Storage::url($complaint->attachment) }}" class="img-fluid rounded-3 border" alt="Lampiran" style="max-height: 250px;">
                        @else
                            <a href="{{ Storage::url($complaint->attachment) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill fw-semibold border-0 bg-primary bg-opacity-10 shadow-sm">
                                <i class="fas fa-file-pdf me-2"></i> Buka Lampiran Document
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Balasan / Tindak Lanjut -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-comments me-2 text-primary"></i>Diskusi & Tindak Lanjut</h5>
                </div>
                <div class="card-body p-4">
                    @forelse($complaint->responses as $response)
                        <div class="d-flex mb-4 p-3 rounded-4 shadow-sm {{ $response->user->role === 'guru' ? 'bg-primary bg-opacity-10 ms-md-5 border-start border-primary border-4' : 'bg-light me-md-5 border-start border-secondary border-4' }}">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas {{ $response->user->role === 'guru' ? 'fa-user-tie text-primary' : 'fa-user text-secondary' }} fa-2x mt-1"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">{{ $response->user->name }} <span class="badge bg-{{ $response->user->role === 'guru' ? 'primary' : 'secondary' }} ms-1 fw-normal rounded-pill" style="font-size: 0.70rem;">{{ ucfirst($response->user->role) }}</span></h6>
                                <p class="text-muted small mb-2"><i class="fas fa-clock me-1"></i> {{ $response->created_at->format('d M Y - H:i') }}</p>
                                <div class="text-dark">{{ $response->message }}</div>
                                
                                @if($response->attachment)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($response->attachment) }}" target="_blank" class="badge bg-white text-primary border rounded-pill text-decoration-none px-2 py-1"><i class="fas fa-paperclip me-1"></i> Ada Lampiran File</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-comment-dots text-muted mb-3 opacity-25 fa-3x"></i>
                            <p class="text-muted fw-semibold mb-0">Belum ada tindakan atau komentar pada aduan ini.</p>
                            <p class="text-muted small">Berikan respon pertama agar siswa tahu laporannya sedang diproses.</p>
                        </div>
                    @endforelse

                    @if(in_array($complaint->status, ['diproses', 'menunggu']))
                        <div class="mt-4 pt-4 border-top">
                            <form action="{{ route('guru.complaints.response', $complaint->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <h6 class="fw-bold mb-3">Beri Tanggapan ke Siswa:</h6>
                                <div class="mb-3">
                                    <textarea name="message" class="form-control border-primary rounded-3 @error('message') is-invalid @enderror" rows="4" placeholder="Ketik tindak lanjut, pertanyaan ke siswa, atau hasil solusi di sini..." required></textarea>
                                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-semibold"><i class="fas fa-paperclip me-1"></i> Lampirkan Foto/Dok Hasil (Opsional)</label>
                                    <input type="file" name="attachment" class="form-control form-control-sm border-0 bg-light rounded-3" accept="image/*,.pdf">
                                    @error('attachment')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                        <i class="fas fa-paper-plane me-2"></i> Kirim Respon
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-secondary border-0 mt-4 text-center rounded-3">
                            <i class="fas fa-lock me-2 text-muted"></i> Pengaduan ini telah <strong>Selesai</strong>. Penambahan komentar ditutup.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar: Status Update -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm border-0 rounded-4 sticky-top bg-success bg-opacity-10 border border-2 border-success" style="top: 2rem;">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-flag-checkered fa-3x text-success mb-3 opacity-75"></i>
                    <h5 class="fw-bold text-success mb-3">Tutup Pengaduan Kasus</h5>
                    <p class="text-muted small mb-4">Jika penanganan sudah selesai dilakukan dan masalah tuntas, ubah status menjadi Selesai.</p>
                    
                    @if($complaint->status !== 'selesai' && $complaint->status !== 'ditolak')
                    <form action="{{ route('guru.complaints.update', $complaint->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow" onclick="return confirm('Tandai pengaduan ini telah selesai? Anda tidak bisa mengulangnya.')">
                            <i class="fas fa-check-circle me-2"></i> Tandai Selesai
                        </button>
                    </form>
                    @else
                        <div class="bg-success text-white p-3 rounded-pill fw-bold border-0 shadow-sm mt-2">
                            <i class="fas fa-check-double me-2"></i> Kasus Selesai
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
