<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    public function cutoffDates(): BelongsToMany
    {
        return $this->belongsToMany(CutoffDate::class, 'orders');
    }

    public function schoolclasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'classes_users', 'user_id', 'class_id')->orderBy('displayorder');
    }

    public function isAdmin(): bool
    {
        return $this->isadmin == 1;
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

    public function getFriendlySchedule(): string
    {
        switch ($this->schedule) {
            /*case 'biweekly':
                return 'Bi-weekly';*/
            case 'monthly':
                return 'Monthly';
            /*case 'monthly-second':
                return 'Monthly';*/
            case 'none':
                return 'Never';
            default:
                throw new Exception("Invalid schedule");
                break;
        }
    }
}
