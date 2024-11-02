<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use \Lab404\Impersonate\Models\Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone', 
        'address1', 
        'address2', 
        'city', 
        'province',
        'postal_code',
        'saveon',
        'coop',
        'saveon_onetime',
        'coop_onetime',
        'payment',
        'deliverymethod',
        'referrer',
        'pickupalt',
        'employee',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    public function cutoffDates(): BelongsToMany
    {
        return $this->belongsToMany(CutoffDate::class, 'orders');
    }

    public function isAdmin(): bool
    {
        return $this->isadmin == 1;
    }
    public function canImpersonate()
    {
        return $this->isAdmin();
    }
    public function canBeImpersonated()
    {
        return !$this->isAdmin();
    }

    public function address(): string
    {
        return implode(' ', [$this->address1, $this->address2, $this->city, $this->province, $this->postal_code]);
    }

    public function getPhone(): string
    {
        return sprintf('(%s) %s-%s', substr($this->phone, 0, 3), substr($this->phone, 3, 3), substr($this->phone, 6));
    }

    public function isMail(): bool
    {
        return $this->deliverymethod == 1;
    }

    public function isCreditcard(): bool
    {
        return $this->payment == 1;
    }
}
