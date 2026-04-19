<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'is_admin',
        'avatar', 'address', 'last_login_at', 'last_login_ip',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
            'last_login_at'     => 'datetime',
        ];
    }

    public function orders(): HasMany {
        return $this->hasMany(Order::class);
    }

    public function addresses(): HasMany {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress() {
        return $this->addresses()->where('is_default', true)->first()
            ?? $this->addresses()->latest()->first();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && str_starts_with($this->avatar, 'http')) return $this->avatar;
        if ($this->avatar) return asset('storage/' . $this->avatar);
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=c0392b&color=fff&length=2&font-size=0.40&bold=true';
    }
}
