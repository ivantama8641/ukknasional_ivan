<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'description',
        'attachment', 'status', 'priority', 'handled_by', 'rejection_reason'
    ];

    // Status labels
    public static $statusLabels = [
        'menunggu'  => ['label' => 'Menunggu',  'badge' => 'warning',  'icon' => 'fas fa-clock'],
        'diproses'  => ['label' => 'Diproses',  'badge' => 'info',     'icon' => 'fas fa-spinner'],
        'selesai'   => ['label' => 'Selesai',   'badge' => 'success',  'icon' => 'fas fa-check-circle'],
        'ditolak'   => ['label' => 'Ditolak',   'badge' => 'danger',   'icon' => 'fas fa-times-circle'],
    ];

    public static $priorityLabels = [
        'rendah'  => ['label' => 'Rendah',  'badge' => 'secondary'],
        'sedang'  => ['label' => 'Sedang',  'badge' => 'warning'],
        'tinggi'  => ['label' => 'Tinggi',  'badge' => 'danger'],
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status]['label'] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return self::$statusLabels[$this->status]['badge'] ?? 'secondary';
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::$priorityLabels[$this->priority]['label'] ?? $this->priority;
    }

    public function getPriorityBadgeAttribute(): string
    {
        return self::$priorityLabels[$this->priority]['badge'] ?? 'secondary';
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class)->latest();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
