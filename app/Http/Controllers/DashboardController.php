<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $total_saldo = Student::selectRaw('sum(balance) as saldo')->value('saldo');

        $total_penarikan = Transaction::where('transaction_type', 'out')
            ->selectRaw('sum(amount) as setoran')
            ->value('setoran');

        $total_setoran = Transaction::where('transaction_type', 'in')
            ->selectRaw('sum(amount) as penarikan')
            ->value('penarikan');

        $card_data = [
            [
                'label' => 'Total Siswa',
                'color' => 'success',
                'data' => Student::count()
            ],
            [
                'label' => 'Total Penarikan',
                'color' => 'purple',
                'data' => number_format($total_penarikan, 0, ',', '.')
            ],
            [
                'label' => 'Total Setoran',
                'color' => 'warning',
                'data' => number_format($total_setoran, 0, ',', '.')
            ],
            [
                'label' => 'Total Saldo',
                'color' => 'error',
                'data' => number_format($total_saldo, 0, ',', '.')
            ]
        ];

        $nama_kelas = Grade::pluck('grade_name');
        $saldo_kelas = Grade::withSum('student', 'balance')
            ->pluck('student_sum_balance');

        $saldo_terbanyak = Student::with('grade')
            ->orderBy('balance', 'desc')->limit(5)->get();

        foreach($saldo_terbanyak as $data) {
            $data->balance = number_format($data->balance, 0, ',', '.');
        }

        return Inertia::render('Dashboard', [
            'cardData' => $card_data,
            'gradeName' => $nama_kelas,
            'gradeBalance' => $saldo_kelas,
            'topBalance' => $saldo_terbanyak,
        ]);
    }
}
