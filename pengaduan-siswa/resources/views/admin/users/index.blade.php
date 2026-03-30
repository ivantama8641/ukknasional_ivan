@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0 text-dark">Manajemen Pengguna</h3>
            <p class="text-muted mb-0">Kelola akun Admin, Guru, dan Siswa</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button class="btn btn-primary shadow-sm border rounded-pill fw-semibold px-4" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-user-plus me-1"></i> Tambah Pengguna
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light shadow-sm border rounded-pill fw-semibold px-3 ms-2">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
        </div>
    @endif

    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $role == 'siswa' ? 'active rounded-pill fw-bold shadow-sm' : 'text-muted fw-semibold' }}" href="{{ route('admin.users.index', ['role' => 'siswa']) }}">Data Siswa</a>
        </li>
        <li class="nav-item ms-2">
            <a class="nav-link {{ $role == 'guru' ? 'active rounded-pill fw-bold shadow-sm' : 'text-muted fw-semibold' }}" href="{{ route('admin.users.index', ['role' => 'guru']) }}">Data Guru/Wali</a>
        </li>
        <li class="nav-item ms-2">
            <a class="nav-link {{ $role == 'admin' ? 'active rounded-pill fw-bold shadow-sm' : 'text-muted fw-semibold' }}" href="{{ route('admin.users.index', ['role' => 'admin']) }}">Administrator</a>
        </li>
    </ul>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama & Kontak</th>
                            <th>No. Induk (NIS/NIP)</th>
                            <th>Departemen/Kelas</th>
                            <th>Status Akses</th>
                            <th class="text-center">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 45px; height: 45px;">
                                            <i class="fas {{ $user->role == 'guru' ? 'fa-user-tie' : ($user->role == 'admin' ? 'fa-user-shield' : 'fa-user') }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                            <div class="small text-muted">{{ $user->email }}</div>
                                            <div class="small text-muted"><i class="fas fa-phone-alt opacity-50 me-1"></i> {{ $user->phone ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-semibold text-muted">{{ $user->nis_nip ?? '-' }}</td>
                                <td>
                                    @if($user->kelas || $user->jurusan)
                                        <span class="badge bg-light text-dark border">{{ $user->kelas }}</span>
                                        <span class="badge bg-light text-dark border mt-1">{{ $user->jurusan }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1"><i class="fas fa-check me-1"></i> Aktif</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-1"><i class="fas fa-lock me-1"></i> Suspend</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle shadow-sm mx-1" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit Data Pengguna">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm mx-1" title="Hapus Akun / Disable" onclick="return confirm('Apakah Anda yakin ingin menghapus atau menonaktifkan pengguna ini secara paksa?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="fw-bold text-dark">Edit Data: {{ $user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Nama Lengkap</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Peran Sistem (Role)</label>
                                                        <select name="role" class="form-select" required>
                                                            <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                                            <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru / Wali Kelas</option>
                                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Ganti Password (Opsional)</label>
                                                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">NIS / NIP</label>
                                                        <input type="text" name="nis_nip" class="form-control" value="{{ $user->nis_nip }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Kelas</label>
                                                        <input type="text" name="kelas" class="form-control" value="{{ $user->kelas }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Jurusan</label>
                                                        <input type="text" name="jurusan" class="form-control" value="{{ $user->jurusan }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">No HP / Telp</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                                    </div>
                                                    <div class="col-12 mt-4">
                                                        <div class="form-check form-switch p-3 bg-light rounded-3 border">
                                                            <input class="form-check-input mt-1 ms-1" type="checkbox" role="switch" name="is_active" id="isActiveEdit{{$user->id}}" value="1" {{ $user->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold ms-3" for="isActiveEdit{{$user->id}}">Izinkan Login (Akun Aktif)</label>
                                                            <div class="small text-muted ms-3 mt-1">Matikan (Suspend) pengguna ini jika melakukan pelanggaran tanpa menghilangkan jejak pelaporannya.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                                    <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Simpan Data</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-users-slash fa-3x text-muted mb-3 opacity-50"></i>
                                    <h5 class="text-muted fw-semibold">Data Pengguna Kosong</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-dark">Registrasi Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap *</label>
                            <input type="text" name="name" class="form-control" required placeholder="Sesuai KTP/Data Pribadi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Login *</label>
                            <input type="email" name="email" class="form-control" required placeholder="Terkait Password Reset">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Peran Sistem (Role) *</label>
                            <select name="role" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Hak Akses --</option>
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru / Wali Kelas</option>
                                <option value="admin">Administrator (Hati-Hati)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Awal *</label>
                            <input type="password" name="password" class="form-control" required placeholder="Min. 8 Karakter Bebas">
                        </div>
                        <hr class="my-4 text-muted opacity-25">
                        <h6 class="fw-bold mb-2">Profil Detail (Opsional)</h6>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">NIS / NIP</label>
                            <input type="text" name="nis_nip" class="form-control" placeholder="Hanya angka">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kelas</label>
                            <input type="text" name="kelas" class="form-control" placeholder="Cth: XII">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" placeholder="Cth: RPL">
                        </div>
                        <div class="col-md-6 pb-2">
                            <label class="form-label fw-semibold">No HP / WhatsApp (Aktif)</label>
                            <input type="text" name="phone" class="form-control" placeholder="Cth: 0812345678">
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                       <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm"><i class="fas fa-save me-2"></i> Daftar User Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
