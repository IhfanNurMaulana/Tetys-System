<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentFormatExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'NIS',
            'NAMA SISWA',
            'JENIS KELAMIN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
        ];
    }

    public function collection()
    {
        return collect([
            ['12345', 'Nama Lengkap', 'L/P', 'Nama Kota', '07-04-2000']
        ]);
    }
}
