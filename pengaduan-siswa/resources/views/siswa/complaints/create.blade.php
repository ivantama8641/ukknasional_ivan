@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold mb-0 text-dark">Buat Pengaduan Baru</h3>
                    <p class="text-muted mb-0">Laporkan masalah, kerusakan fasilitas, atau kendala sekolah di sini.</p>
                </div>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-light shadow-sm border rounded-pill fw-semibold px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('siswa.complaints.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-semibold">Pilih Kategori <span class="text-danger">*</span></label>
                            <select class="form-select border-start-0 border-end-0 border-top-0 border-primary rounded-0 px-0 fw-semibold bg-transparent @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="" selected disabled>-- Silakan Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">Judul Pengaduan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-start-0 border-end-0 border-top-0 border-primary rounded-0 px-0 fw-semibold bg-transparent @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Tuliskan judul singkat terkait aduan Anda..." required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Detail Aduan <span class="text-danger">*</span></label>
                            <textarea class="form-control border-primary rounded-3 @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Ceritakan detail masalah yang Anda alami secara jelas dan lengkap..." required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text text-muted">Jelaskan waktu, tempat kejadian (jika relevan), dan kronologi.</div>
                        </div>

                        <div class="mb-4">
                            <label for="attachment" class="form-label fw-semibold">Unggah Bukti (Opsional)</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror" id="attachment" name="attachment" accept="image/*,.pdf">
                            @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text text-muted">Maksimal 2 MB. Format: JPG, PNG, PDF.</div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill shadow-sm">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Pengaduan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Rules Information -->
            <div class="alert alert-info border-0 shadow-sm rounded-4 mt-4 p-4 d-flex">
                <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                <div>
                    <h6 class="fw-bold mb-1">Penting!</h6>
                    <p class="mb-0 small">Harap menulis pengaduan yang beretika, jelas, dan mematuhi peraturan sekolah. Laporan palsu mungkin akan dikenakan sanksi tata tertib sekolahan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
