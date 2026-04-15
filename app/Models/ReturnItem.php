<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'staff_id',
        'return_date',
        'condition',
        'notes',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
