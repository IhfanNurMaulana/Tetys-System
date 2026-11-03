<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_name',
    ];

    // Tambahkan method relasi ke Student di sini (sesuai buku halaman 286)
    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
