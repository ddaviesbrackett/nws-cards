<?php

namespace App\Models;

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

    public function orders() : HasMany
    {
        return $this->hasMany('Order')->orderBy('created_at', 'desc');
    }

    public function cutoffDates() : BelongsToMany
    {
        return $this->belongsToMany('CutoffDate', 'orders');
    }

    public function schoolclasses() : BelongsToMany
    {
        return $this->belongsToMany('SchoolClass', 'classes_users', 'user_id', 'class_id')->orderBy('displayorder');
    }

    public function isAdmin() : bool
    {
        return true; //TODO update with isAdmin flag in Users rather than using Sentry groups
    }

    public function address() : string
    {
		return implode(' ', [$this->address1, $this->address2, $this->city, $this->province, $this->postal_code]);
	}

	public function getPhone() : string
    {
		return sprintf('(%s) %s-%s', substr($this->phone, 0, 3),substr($this->phone, 3, 3), substr($this->phone, 6)) ;
	}

    public function isMail() :bool
    {
		return $this->deliverymethod == 1;
	}

    public function isCreditcard() : bool
    {
        return $this->payment == 1;
    }
}
