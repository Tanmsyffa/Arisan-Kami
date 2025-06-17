<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role' // Pastikan kolom 'role' ada di tabel users
    ];

    protected $hidden = ['password', 'remember_token'];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Tambahkan method hasRole() berikut
    public function hasRole($role): bool
    {
        // Periksa apakah role user sama dengan yang diminta
        return $this->role === $role;
    }

    // Opsional: Method untuk memeriksa multiple roles
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }
}