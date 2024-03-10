<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pointsale extends Model
{
    use HasFactory;

    protected $table = 'pointsales';
	protected $dates = ['created_at', 'updated_at', 'saledate'];

	protected $fillable = [
		'payment',
		'saveon_dollars',
		'coop_dollars',
		'paid',
		'saledate',
	];

	public function schoolclasses() : BelongsToMany
    {
		return $this->belongsToMany('SchoolClass', 'classes_pointsales', 'pointsale_id', 'class_id')->withPivot('profit');
	}

	public function isCreditcard() :bool
    {
		return $this->payment == 1;
	}
}
