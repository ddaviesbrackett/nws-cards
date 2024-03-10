<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
	protected $table = 'classes';

	protected $fillable = [
		'name',
		'bucketname',
	];

	public static function choosable() : Collection
    {
		return SchoolClass::where('displayorder', '>', 0)->orderBy('displayorder', 'asc')->get();
	}

	public function expenses() : HasMany
    {
		return $this->hasMany('Expense', 'class_id');
	}

	public function pointsales() : BelongsToMany
    {
		return $this->belongsToMany('Pointsale', 'classes_pointsales', 'class_id', 'pointsale_id')->withPivot('profit');
	}

	public function orders() : BelongsToMany
    {
		return $this->belongsToMany('Order', 'classes_orders', 'class_id', 'order_id')->withPivot('profit');
	}

	public function users() : BelongsToMany
    {
		return $this->belongsToMany('User', 'classes_users', 'class_id', 'user_id');
	}
}
