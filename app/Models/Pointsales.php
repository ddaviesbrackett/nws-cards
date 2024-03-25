<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;

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
