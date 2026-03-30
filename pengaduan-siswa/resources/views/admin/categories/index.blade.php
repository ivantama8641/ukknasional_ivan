@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0 text-dark">Data Kategori Pengaduan</h3>
            <p class="text-muted mb-0">Kelola master data kategori laporan aduan</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button class="btn btn-primary shadow-sm border rounded-pill fw-semibold px-4" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="fas fa-plus me-1"></i> Tambah Kategori
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
    
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Preview</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi Tambahan</th>
                            <th>Status Aktif</th>
                            <th class="text-center">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm" style="width: 45px; height: 45px; background-color: {{ $category->color }};">
                                        <i class="{{ $category->icon }} fa-lg"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $category->name }}</div>
                                    <div class="small text-muted font-monospace">{{ $category->icon }} / {{ $category->color }}</div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($category->description, 50) ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i> Aktif</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2"><i class="fas fa-times me-1"></i> Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle shadow-sm mx-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}" title="Edit Kategori">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm mx-1" title="Hapus Permanen" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal for this loop -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="fw-bold text-dark">Edit Data Kategori</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Kategori</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Keterangan / Deskripsi</label>
                                                    <input type="text" name="description" class="form-control" value="{{ $category->description }}">
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Kode Icon (FontAwesome)</label>
                                                        <input type="text" name="icon" class="form-control" value="{{ $category->icon }}" required placeholder="Contoh: fas fa-book">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Kode Warna (Hex)</label>
                                                        <div class="input-group">
                                                            <input type="text" name="color" class="form-control" value="{{ $category->color }}" required oninput="document.getElementById('editColorPrev{{$category->id}}').style.backgroundColor = this.value">
                                                            <span class="input-group-text" id="editColorPrev{{$category->id}}" style="background-color: {{ $category->color }}; width: 40px;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch mb-4">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="isActiveEdit{{$category->id}}" value="1" {{ $category->is_active ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="isActiveEdit{{$category->id}}">Kategori Aktif (Bisa Dipilih Siswa)</label>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">Simpan Perubahan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-tags fa-3x text-muted mb-3 opacity-50"></i>
                                    <h5 class="text-muted fw-semibold">Belum Ada Kategori</h5>
                                    <p class="text-muted small">Tambahkan kategori baru agar siswa bisa melapor ke sekolah.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-dark">Tambah Kategori Aduan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Bimbingan Konseling / Bullying">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan / Deskripsi</label>
                        <input type="text" name="description" class="form-control" placeholder="Opsional...">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Kode FontAwesome</label>
                            <input type="text" name="icon" class="form-control" required value="fas fa-folder">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Warna Label (Hex)</label>
                            <input type="color" name="color" class="form-control form-control-color w-100" required value="#3b82f6" title="Pilih warna Anda">
                        </div>
                    </div>
                    <!-- Hidden active status -->
                    <input type="hidden" name="is_active" value="1">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill mt-3 fw-bold shadow-sm">Tambahkan Kategori</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
