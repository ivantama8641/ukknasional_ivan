<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['user', 'category'])
            ->where('handled_by', auth()->id())
            ->latest()
            ->paginate(15);
            
        return view('guru.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        // Allow Guru to see any complaint, or Admin
        if (auth()->user()->role !== 'guru' && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $complaint->load(['user', 'category', 'responses.user']);
        
        return view('guru.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        if (auth()->user()->role !== 'guru') abort(403);

        // Auto assign if not yet handled
        if (!$complaint->handled_by) {
            $complaint->handled_by = auth()->id();
        }

        $validated = $request->validate([
            'status' => 'required|in:diproses,selesai'
        ]);

        $old_status = $complaint->status;
        $complaint->update($validated);

        if ($old_status !== $complaint->status) {
            \App\Models\Notification::create([
                'user_id' => $complaint->user_id,
                'complaint_id' => $complaint->id,
                'title' => 'Update Status Pengaduan',
                'message' => "Guru telah mengubah status pengaduan Anda '" . $complaint->title . "' menjadi " . ucfirst($complaint->status),
                'type' => $complaint->status == 'selesai' ? 'success' : 'info',
            ]);
        }

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }

    public function storeResponse(Request $request, Complaint $complaint)
    {
        // Any Guru or the reporting Siswa or Admin can respond
        if (auth()->user()->role !== 'guru' && auth()->user()->role !== 'admin' && $complaint->user_id !== auth()->id()) {
            abort(403);
        }

        // Auto assign to Guru if they respond and it's unassigned
        if (auth()->user()->role === 'guru' && !$complaint->handled_by) {
            $complaint->update(['handled_by' => auth()->id()]);
        }

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'complaint_id' => $complaint->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('responses', 'public');
        }

        ComplaintResponse::create($data);

        // Notify Student or Admin/Guru
        if (auth()->user()->role == 'siswa') {
            // Notify Guru if assigned, else notify Admin? (Keeping it simple for now)
            if ($complaint->handled_by) {
                \App\Models\Notification::create([
                    'user_id' => $complaint->handled_by,
                    'complaint_id' => $complaint->id,
                    'title' => 'Respon Baru dari Siswa',
                    'message' => "Siswa " . auth()->user()->name . " membalas pengaduan: " . $complaint->title,
                    'type' => 'info',
                ]);
            }
        } else {
            // Notify Siswa
            \App\Models\Notification::create([
                'user_id' => $complaint->user_id,
                'complaint_id' => $complaint->id,
                'title' => 'Respon Baru dari Guru/Admin',
                'message' => "Ada tanggapan baru untuk pengaduan Anda: " . $complaint->title,
                'type' => 'info',
            ]);
        }

        // Jika statusnya menunggu, otomatis ubah jadi diproses
        if ($complaint->status === 'menunggu' && auth()->user()->role !== 'siswa') {
            $complaint->update(['status' => 'diproses']);
        }

        return redirect()->back()->with('success', 'Berhasil menambahkan respon.');
    }
}
