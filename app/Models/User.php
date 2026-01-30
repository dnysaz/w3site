<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\AiLog;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'social_id',
        'social_type',
        'avatar',
        'status',
        'package',            // Tambahan: 0, 1, 2, 3
        'email_verified_at',
        'package_expired_at', // Tambahan: Masa aktif paket
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'package_expired_at' => 'datetime', // Otomatis jadi objek Carbon
            'package' => 'integer',             // Pastikan selalu dibaca angka
        ];
    }

    /**
     * Helper untuk mengecek akses AI Landing Page Builder.
     * Hanya bisa diakses oleh paket 3 (Developer).
     */
    public function canAccessAiBuilder(): bool
    {
        return $this->package >= 3;
    }

    /**
     * Mendapatkan nama paket dalam bentuk teks.
     * Contoh penggunaan: $user->package_name
     */
    public function getPackageNameAttribute(): string
    {
        return match($this->package) {
            1 => 'Pemula',
            2 => 'Pro',
            default => 'Gratis',
        };
    }

    /**
     * Helper untuk mengecek apakah paket berbayar masih aktif.
     */
    public function isPackageActive(): bool
    {
        // Jika paket 0 (Gratis), kita anggap tidak ada "paket berbayar" yang aktif
        if ($this->package == 0) return false;
        
        // Jika tidak punya tanggal expired, kita anggap tidak aktif
        if (!$this->package_expired_at) return false;
        
        // Mengecek apakah tanggal expired masih di masa depan
        return $this->package_expired_at->isFuture();
    }

    /**
     * Menghitung sisa hari paket.
     */
    public function daysRemaining(): int
    {
        if (!$this->package_expired_at) return 0;
        
        // Mengembalikan angka (contoh: 5, 10, atau 0)
        return (int) now()->diffInDays($this->package_expired_at, false);
    }

    /**
     * Helper untuk mengecek Role Admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    /**
     * Helper untuk mengecek apakah akun aktif secara sistem.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function aiBlogs()
    {
        return $this->hasMany(AiBlog::class);
    }

    public function getAiStats()
    {
        $limits = [0 => 10, 1 => 100, 2 => 1000];
        $limit = $limits[$this->package] ?? 0;
        
        $usage = AiLog::where('user_id', $this->id)
            ->whereMonth('created_at', now()->month)
            ->count();

        return [
            'used' => $usage,
            'limit' => $limit,
            'remaining' => max(0, $limit - $usage),
            'percentage' => ($usage / $limit) * 100
        ];
    }

    // Linktree
    public function linktrees()
    {
        return $this->hasMany(Linktree::class);
    }

    /**
     * Relasi ke Transactions: Satu user bisa memiliki banyak transaksi.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}