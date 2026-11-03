<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentImport implements ToModel, WithHeadingRow
{
    public $grade_id;

    public function __construct($grade_id)
    {
        $this->grade_id = $grade_id;
    }

    public function model(array $row)
    {
        // Menangani tanggal lahir dari Excel secara fleksibel & aman
        $dateOfBirth = null;
        if (!empty($row['tanggal_lahir'])) {
            if (is_numeric($row['tanggal_lahir'])) {
                $dateOfBirth = Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
            } else {
                // Membersihkan spasi dan mengubah pemisah / atau . menjadi -
                $cleanDate = trim(str_replace(['/', '.'], '-', $row['tanggal_lahir']));
                $dateOfBirth = date('Y-m-d', strtotime($cleanDate));
            }
        }

        return new Student([
            'nis' => trim($row['nis']),
            'name' => trim($row['nama_siswa']),
            'gender' => trim($row['jenis_kelamin']),
            'place_of_birth' => trim($row['tempat_lahir']),
            'date_of_birth' => $dateOfBirth,
            'grade_id' => $this->grade_id,
        ]);
    }
}
