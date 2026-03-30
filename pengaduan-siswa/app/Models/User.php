<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'nis_nip', 'kelas', 'jurusan', 'phone', 'foto', 'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Roles
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isGuru(): bool  { return $this->role === 'guru'; }
    public function isSiswa(): bool { return $this->role === 'siswa'; }

    // Relations
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function handledComplaints()
    {
        return $this->hasMany(Complaint::class, 'handled_by');
    }

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }
}
