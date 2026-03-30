<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .navbar { background-color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .card { border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-radius: 12px; }
        .btn-primary { background-color: #4f46e5; border-color: #4f46e5; border-radius: 8px; }
        .btn-primary:hover { background-color: #4338ca; border-color: #4338ca; }
        .form-control { border-radius: 8px; }
        .form-control:focus { box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25); border-color: #4f46e5; }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(Auth::user()->role == 'admin')
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" href="{{ route('admin.dashboard') }}"><i class="fas fa-home me-1"></i> Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" href="{{ route('admin.complaints.index') }}"><i class="fas fa-bullhorn me-1"></i> Pengaduan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" href="{{ route('admin.categories.index') }}"><i class="fas fa-tags me-1"></i> Kategori</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" href="{{ route('admin.users.index') }}"><i class="fas fa-users me-1"></i> Pengguna</a>
                                </li>
                            @elseif(Auth::user()->role == 'guru')
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" href="{{ route('guru.dashboard') }}"><i class="fas fa-home me-1"></i> Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" href="{{ route('guru.complaints.index') }}"><i class="fas fa-tasks me-1"></i> Tugas Saya</a>
                                </li>
                            @elseif(Auth::user()->role == 'siswa')
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" href="{{ route('siswa.dashboard') }}"><i class="fas fa-home me-1"></i> Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" href="{{ route('siswa.complaints.create') }}"><i class="fas fa-plus-circle me-1"></i> Buat Laporan</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown me-3">
                                <a id="notificationsDropdown" class="nav-link position-relative pe-0" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-lg text-muted"></i>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; margin-top: 5px; margin-left: -5px;">
                                            {{ Auth::user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-0 mt-3" style="width: 320px;" aria-labelledby="notificationsDropdown">
                                    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0 text-dark">Notifikasi</h6>
                                        @if(Auth::user()->unreadNotifications->count() > 0)
                                            <a href="#" class="small text-decoration-none text-primary fw-semibold" onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();">Baca Semua</a>
                                            <form id="mark-all-read-form" action="{{ route('notifications.markAllRead') }}" method="POST" class="d-none">@csrf</form>
                                        @endif
                                    </div>
                                    <div style="max-height: 350px; overflow-y: auto;">
                                        @forelse(Auth::user()->notifications->take(10) as $noti)
                                            <a class="dropdown-item px-4 py-3 border-bottom {{ $noti->is_read ? 'opacity-75' : 'bg-primary bg-opacity-10' }}" href="{{ route('notifications.read', $noti->id) }}">
                                                <div class="d-flex align-items-center mb-1">
                                                    <div class="fw-bold small text-dark">{{ $noti->title }}</div>
                                                    @if(!$noti->is_read)
                                                        <span class="ms-auto p-1 bg-primary border border-light rounded-circle"></span>
                                                    @endif
                                                </div>
                                                <div class="small text-muted text-wrap">{{ Str::limit($noti->message, 100) }}</div>
                                                <div class="x-small text-muted mt-2 d-flex align-items-center" style="font-size: 0.7rem;">
                                                    <i class="far fa-clock me-1"></i> {{ $noti->created_at->diffForHumans() }}
                                                </div>
                                            </a>
                                        @empty
                                            <div class="py-5 text-center px-4">
                                                <i class="fas fa-bell-slash fa-2x text-muted mb-3 opacity-25"></i>
                                                <p class="text-muted small mb-0">Belum ada notifikasi untuk Anda.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="p-2 text-center border-top">
                                        <a class="text-decoration-none small fw-bold text-primary" href="{{ route('notifications.index') }}">Lihat Semua Notifikasi</a>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-bold text-dark d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4f46e5&color=fff" class="rounded-circle me-2" width="32" height="32">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-3" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
