<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('category', 'handler')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return view('siswa.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('siswa.complaints.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'attachment'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2056',
        ]);

        $data = $request->except('attachment');
        $data['user_id'] = auth()->id();
        $data['status'] = 'menunggu';

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('complaints', 'public');
            $data['attachment'] = $path;
        }

        $complaint = Complaint::create($data);

        // Notify Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'complaint_id' => $complaint->id,
                'title' => 'Pengaduan Baru',
                'message' => "Ada pengaduan baru dari " . auth()->user()->name . ": " . $complaint->title,
                'type' => 'warning',
            ]);
        }

        return redirect()->route('siswa.dashboard')->with('success', 'Pengaduan berhasil dikirim! Menunggu konfirmasi dari pihak sekolah.');
    }

    public function show(Complaint $complaint)
    {
        if ($complaint->user_id !== auth()->id()) {
            abort(403);
        }

        $complaint->load(['category', 'handler', 'responses.user']);
        
        return view('siswa.complaints.show', compact('complaint'));
    }

    public function storeResponse(Request $request, Complaint $complaint)
    {
        if ($complaint->user_id !== auth()->id()) abort(403);
        if (in_array($complaint->status, ['selesai', 'ditolak'])) {
            return redirect()->back()->with('error', 'Pengaduan sudah ditutup. Tidak dapat menambahkan komentar.');
        }

        $request->validate(['message' => 'required|string']);

        \App\Models\ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Notify Handler (Guru) or Admin
        if ($complaint->handled_by) {
            \App\Models\Notification::create([
                'user_id' => $complaint->handled_by,
                'complaint_id' => $complaint->id,
                'title' => 'Balasan dari Siswa',
                'message' => "Siswa " . auth()->user()->name . " membalas pengaduan: " . $complaint->title,
                'type' => 'info',
            ]);
        } else {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'complaint_id' => $complaint->id,
                    'title' => 'Balasan Baru (Siswa)',
                    'message' => "Siswa " . auth()->user()->name . " mengirim balasan pada pengaduan: " . $complaint->title,
                    'type' => 'info',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Berhasil menambahkan balasan.');
    }
}
