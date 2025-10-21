<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_id',
        'user_id',
        'days_late',
        'amount',
        'is_paid',
    ];

    // Relasi ke Borrow
    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
