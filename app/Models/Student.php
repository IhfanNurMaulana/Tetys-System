<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'name',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'grade_id',
        'photo',
        'balance',
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }
    public function transaction()
{
    return $this->hasMany(Transaction::class);
}
}
