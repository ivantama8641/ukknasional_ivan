<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['user', 'category', 'handler'])
            ->latest()
            ->paginate(15);
            
        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'category', 'handler', 'responses.user']);
        $gurus = User::where('role', 'guru')->where('is_active', true)->get();
        
        return view('admin.complaints.show', compact('complaint', 'gurus'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai,ditolak',
            'priority' => 'nullable|in:rendah,sedang,tinggi',
            'handled_by' => 'nullable|exists:users,id',
            'rejection_reason' => 'nullable|string|required_if:status,ditolak'
        ]);

        $old_status = $complaint->status;
        $old_handler = $complaint->handled_by;
        
        $complaint->update($validated);

        // Notify Student if Status changed
        if ($old_status !== $complaint->status) {
            \App\Models\Notification::create([
                'user_id' => $complaint->user_id,
                'complaint_id' => $complaint->id,
                'title' => 'Update Status Pengaduan',
                'message' => "Status pengaduan Anda '" . $complaint->title . "' telah diubah menjadi " . ucfirst($complaint->status),
                'type' => $complaint->status == 'selesai' ? 'success' : ($complaint->status == 'ditolak' ? 'danger' : 'info'),
            ]);
        }

        // Notify Guru if freshly assigned
        if ($complaint->handled_by && $old_handler !== (int)$complaint->handled_by) {
            \App\Models\Notification::create([
                'user_id' => $complaint->handled_by,
                'complaint_id' => $complaint->id,
                'title' => 'Tugas Pengaduan Baru',
                'message' => "Anda telah ditugaskan untuk menangani pengaduan: " . $complaint->title,
                'type' => 'warning',
            ]);
        }

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }
}
