<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'paid',
        'payment',
        'profit',
        'saveon',
        'coop',
        'saveon_onetime',
        'coop_onetime',
        'deliverymethod',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cutoffdate(): BelongsTo
    {
        return $this->belongsTo(CutoffDate::class, 'cutoff_date_id');
    }

    public function schoolclasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'classes_orders', 'order_id', 'class_id')->withPivot('profit')->orderBy('displayorder');
    }

    public function isCreditcard(): bool
    {
        return $this->payment == 1;
    }

    public function totalCards(): int
    {
        return $this->coop + $this->saveon + $this->coop_onetime + $this->saveon_onetime;
    }

    public function hasOnetime(): bool
    {
        return $this->coop_onetime + $this->saveon_onetime > 0;
    }

    public function newCollection(array $models = []): Collection
    {
        return new Orders($models);
    }
}
