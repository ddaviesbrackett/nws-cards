<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;
    protected $table = 'expenses';

    protected $fillable = [
        'expense_date',
        'amount',
        'description',
        'class_id',
    ];

    public function schoolclass() : BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function getDates()
    {
        return ['expense_date', 'updated_at', 'created_at'];
    }
}
