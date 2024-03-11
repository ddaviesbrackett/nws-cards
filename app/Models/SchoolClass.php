<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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

	public static function profitSince( Carbon $since) : float
	{
		return DB::scalar("
			select sum(co.profit) from classes_orders co
			inner join orders o on o.id = co.order_id
			where o.created_at > ?", [$since]);
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
