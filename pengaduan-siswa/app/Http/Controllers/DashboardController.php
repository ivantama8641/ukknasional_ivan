<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function redirect()
    {
        $user = auth()->user();
        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'guru')  return redirect()->route('guru.dashboard');
        return redirect()->route('siswa.dashboard');
    }

    public function admin()
    {
        $stats = [
            'total'     => Complaint::count(),
            'menunggu'  => Complaint::where('status', 'menunggu')->count(),
            'diproses'  => Complaint::where('status', 'diproses')->count(),
            'selesai'   => Complaint::where('status', 'selesai')->count(),
            'ditolak'   => Complaint::where('status', 'ditolak')->count(),
            'users'     => User::where('role', '!=', 'admin')->count(),
        ];
        
        $recentComplaints = Complaint::with(['user', 'category'])->latest()->take(5)->get();
        
        return view('dashboard.admin', compact('stats', 'recentComplaints'));
    }

    public function guru()
    {
        $stats = [
            'total'     => Complaint::count(),
            'ditangani' => Complaint::where('handled_by', auth()->id())->count(),
        ];
        
        $recentComplaints = Complaint::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard.guru', compact('stats', 'recentComplaints'));
    }

    public function siswa()
    {
        $myComplaints = Complaint::with('category')->where('user_id', auth()->id())->latest()->get();
        
        $stats = [
            'total'    => $myComplaints->count(),
            'menunggu' => $myComplaints->where('status', 'menunggu')->count(),
            'selesai'  => $myComplaints->where('status', 'selesai')->count(),
        ];
        
        return view('dashboard.siswa', compact('myComplaints', 'stats'));
    }
}
