<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

//a vestigial feature. Allowed for tracking of speculatively-bought cards at the school gate; no longer done.
class Pointsales extends Collection
{
    public function getTotalProfit()
    {
        return $this->reduce(function ($total, $order) {
            $current = $order->pivot ? $order->pivot->profit : 0;
            return $total + $current;
        });
    }
}
