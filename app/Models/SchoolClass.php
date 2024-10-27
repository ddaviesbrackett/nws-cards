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

    public static function current() : Collection
    {
        return SchoolClass::where('current', True)->orderBy('displayOrder', 'asc')->get();
    }

    public static function profitSince( Carbon $since) : float
    {
        return DB::scalar("
            select sum(co.profit) from classes_orders co
            inner join orders o on o.id = co.order_id
            where o.created_at > ?", [$since]);
    }

    public function profitByCutoff()
    {
        return array_map(function($row){
            return [
                'date' => new Carbon($row->delivery),
                'profit' => $row->profit
            ];
        }, DB::select('
            select cd.delivery as delivery, sum(co.profit) as profit
            from cutoffdates cd
            inner join orders o on o.cutoff_date_id = cd.id
            inner join classes_orders co on co.order_id = o.id
            where co.class_id = ?
            group by cd.delivery
            order by cd.delivery desc', [$this->id]));
    }

    public function expenses() : HasMany
    {
        return $this->hasMany(Expense::class, 'class_id');
    }

    public function pointsales() : BelongsToMany
    {
        return $this->belongsToMany(Pointsale::class, 'classes_pointsales', 'class_id', 'pointsale_id')->withPivot('profit');
    }

    public function orders() : BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'classes_orders', 'class_id', 'order_id')->withPivot('profit');
    }
}
