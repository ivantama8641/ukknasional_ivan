@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h3 class="text-center fw-bold mb-3" style="color: #4f46e5;">Daftar Akun Siswa</h3>
                    <p class="text-center text-muted mb-4 pb-2 border-bottom">Lengkapi data diri untuk membuat pengaduan</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Contoh: Budi Santoso">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nis_nip" class="form-label fw-semibold">NIS (Nomor Induk Siswa)</label>
                                <input id="nis_nip" type="text" class="form-control @error('nis_nip') is-invalid @enderror" name="nis_nip" value="{{ old('nis_nip') }}" required placeholder="Contoh: 12345678">
                                @error('nis_nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">No. HP / WhatsApp</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="Contoh: 0812...">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="kelas" class="form-label fw-semibold">Kelas</label>
                                <input id="kelas" type="text" class="form-control @error('kelas') is-invalid @enderror" name="kelas" value="{{ old('kelas') }}" required placeholder="Contoh: X, XI, XII">
                                @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="jurusan" class="form-label fw-semibold">Jurusan</label>
                                <input id="jurusan" type="text" class="form-control @error('jurusan') is-invalid @enderror" name="jurusan" value="{{ old('jurusan') }}" required placeholder="Contoh: RPL, TKJ">
                                @error('jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12 mt-4">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@contoh.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label fw-semibold">Konfirmasi Password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                            </div>

                            <div class="col-md-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 btn-lg fw-semibold shadow-sm">
                                    <i class="fas fa-user-plus me-2"></i> {{ __('Buat Akun Siswa') }}
                                </button>
                            </div>

                            <div class="col-md-12 text-center mt-3">
                                <p class="text-muted small mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: #4f46e5;">Masuk di sini</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
