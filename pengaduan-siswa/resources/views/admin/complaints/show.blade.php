@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold mb-0 text-dark">Manajemen Pengaduan #{{ str_pad($complaint->id, 4, '0', STR_PAD_LEFT) }}</h3>
            <p class="text-muted mb-0">Laporkan oleh {{ $complaint->user->name }} pada {{ $complaint->created_at->format('d M Y - H:i') }}</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-light shadow-sm border rounded-pill fw-semibold px-3">
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
        <!-- Main Info -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-dark">Detail Laporan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 d-flex align-items-center">
                         <span class="badge" style="background-color: {{ $complaint->category->color }}20; color: {{ $complaint->category->color }}; border: 1px solid {{ $complaint->category->color }}50; padding: 0.5rem 1rem; border-radius: 50rem;">
                            <i class="{{ $complaint->category->icon }} me-1"></i> {{ $complaint->category->name }}
                        </span>
                    </div>

                    <h4 class="fw-bold mb-3">{{ $complaint->title }}</h4>
                    <div class="bg-light p-4 rounded-3 text-dark mb-4" style="white-space: pre-wrap; font-size: 1.05rem;">{{ $complaint->description }}</div>

                    @if($complaint->attachment)
                        <h6 class="fw-bold mb-2"><i class="fas fa-paperclip text-muted me-2"></i>Bukti Lapisan</h6>
                        @if(Str::endsWith(strtolower($complaint->attachment), ['.jpg', '.jpeg', '.png']))
                            <img src="{{ Storage::url($complaint->attachment) }}" class="img-fluid rounded-3 border" alt="Lampiran" style="max-height: 350px;">
                        @else
                            <a href="{{ Storage::url($complaint->attachment) }}" target="_blank" class="btn btn-outline-primary rounded-pill fw-semibold bg-primary bg-opacity-10 shadow-sm border-0">
                                <i class="fas fa-file-pdf me-2"></i> Unduh Lampiran
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Profile Pelapor -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">Informasi Pelapor</h5>
                    <div class="d-flex border p-3 rounded-4">
                        <div class="me-3">
                            <i class="fas fa-user-circle fa-4x text-muted opacity-50"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">{{ $complaint->user->name }}</h5>
                            <p class="mb-1 text-muted small"><i class="fas fa-id-card me-2"></i>NIS/NIP: {{ $complaint->user->nis_nip ?? '-' }}</p>
                            <p class="mb-1 text-muted small"><i class="fas fa-graduation-cap me-2"></i>Kelas/Jurusan: {{ $complaint->user->kelas }} - {{ $complaint->user->jurusan }}</p>
                            <p class="mb-0 text-muted small"><i class="fas fa-phone-alt me-2"></i>Telepon: {{ $complaint->user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Form & Priority -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 sticky-top bg-light border border-2 border-primary" style="top: 2rem;">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-cog me-2"></i>Manajemen Status</h5>
                    
                    <form action="{{ route('admin.complaints.update', $complaint->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Status Selection -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold text-dark">Ubah Status</label>
                            <select class="form-select border-start-0 border-end-0 border-top-0 border-primary rounded-0 fw-bold bg-transparent" id="status" name="status" onchange="toggleRejectionReason()">
                                @foreach(['menunggu' => 'Menunggu (Masuk)', 'diproses' => 'Sedang Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak (Spam/Palsu)'] as $val => $label)
                                    <option value="{{ $val }}" {{ $complaint->status == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rejection Reason (Hidden by default) -->
                        <div class="mb-4" id="rejection_container" style="display: {{ $complaint->status == 'ditolak' ? 'block' : 'none' }};">
                            <label for="rejection_reason" class="form-label fw-semibold text-danger">Alasan Penolakan</label>
                            <textarea class="form-control rounded-3 border-danger" id="rejection_reason" name="rejection_reason" rows="2" placeholder="Wajib diisi jika ditolak!">{{ $complaint->rejection_reason }}</textarea>
                        </div>

                        <!-- Priority -->
                        <div class="mb-4">
                            <label for="priority" class="form-label fw-semibold text-dark">Tingkat Prioritas</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['rendah' => 'success', 'sedang' => 'warning', 'tinggi' => 'danger'] as $val => $color)
                                    <input type="radio" class="btn-check" name="priority" id="prio_{{ $val }}" value="{{ $val }}" {{ $complaint->priority == $val ? 'checked' : '' }}>
                                    <label class="btn btn-outline-{{ $color }} rounded-pill px-3 fw-semibold shadow-sm" for="prio_{{ $val }}">
                                        {{ ucfirst($val) }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Assign Guru (Handled By) -->
                        <div class="mb-5">
                            <label for="handled_by" class="form-label fw-semibold text-dark">Tugaskan Penanganan Kepada:</label>
                            <select class="form-select border-start-0 border-end-0 border-top-0 border-primary bg-transparent rounded-0 fw-semibold" id="handled_by" name="handled_by">
                                <option value="">-- Belum Ditunjuk (Biarkan Kosong) --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ $complaint->handled_by == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small text-muted">Guru yang ditunjuk akan dapat mengakses dan memberikan respons.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                                Simpan Perubahan <i class="fas fa-save ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRejectionReason() {
    var status = document.getElementById('status').value;
    var container = document.getElementById('rejection_container');
    var input = document.getElementById('rejection_reason');
    
    if (status === 'ditolak') {
        container.style.display = 'block';
        input.required = true;
    } else {
        container.style.display = 'none';
        input.required = false;
        input.value = '';
    }
}
</script>
@endsection
