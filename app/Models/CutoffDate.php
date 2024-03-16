<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CutoffDate extends Model
{
    use HasFactory;
    protected $table = 'cutoffdates';

	protected $fillable = [
		'saveon_cheque_value',
		'saveon_card_value',
		'coop_cheque_value',
		'coop_card_value',
	];

	public function getSaveonChequeValueAttribute($val) : float
    {
		return floatval($val);
	}
	public function getSaveonCardValueAttribute($val) : float
    {
		return floatval($val);
	}
	public function getCoopChequeValueAttribute($val) : float
    {
		return floatval($val);
	}
	public function getCoopCardValueAttribute($val) : float
    {
		return floatval($val);
	}

	public function orders() : HasMany
    {
		return $this->hasMany(Order::class);
	}

	public function users() : BelongsToMany
    {
		return $this->belongsToMany(User::class, 'orders');
	}

	public function cutoffdate() : Carbon
    {
		return new Carbon($this->cutoff, 'America/Los_Angeles');
	}
	public function chargedate(): Carbon
    {
		return new Carbon($this->charge, 'America/Los_Angeles');
	}

	public function deliverydate(): Carbon
    {
		return new Carbon($this->delivery, 'America/Los_Angeles');
	}

    public function dates() : Array
    {
        return [
            'cutoff' => $this->cutoffdate(),
            'charge' => $this->chargedate(),
            'delivery' => $this->deliverydate()
        ];
    }
}
