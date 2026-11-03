<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'transaction_date',
        'transaction_type',
        'amount',
        'balance',
        'description',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
