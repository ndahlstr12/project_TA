<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'siswa_id',
        'guru_id',
        'must_change_password',
        'foto'
    ];

    public function passwordResetRequests()
    {
        return $this->hasMany(PasswordResetRequest::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    const ROLE_ADMIN = 'admin';
    const ROLE_GURU = 'guru';
    const ROLE_WALIKELAS = 'walikelas';
    const ROLE_SISWA = 'siswa';
    const ROLE_ORANGTUA = 'orangtua';

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function orangTua()
    {
        return $this->hasOne(OrangTua::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }
}
