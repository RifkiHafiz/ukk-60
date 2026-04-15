<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_code',
        'loan_date',
        'return_date',
        'status',
        'notes',
        'rejected_reason',
        'quantity',
        'staff_id',
        'borrower_id',
        'item_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function returnItem()
    {
        return $this->hasOne(ReturnItem::class);
    }
}
