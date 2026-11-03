<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Transaction;

class ReportController extends Controller
{
    // Ambil semua data siswa
    public function index(Request $request)
    {
        $grades = Grade::all();
        $students = null;
        $grade = 0;

        if (session('grade')) {
            $grade = session('grade');
            $students = Student::where('grade_id', $grade)->get();
        }

        return Inertia::render('Report/Index', [
            'grade' => $grade,
            'grades' => $grades,
            'students' => Inertia::lazy(fn () => $students),
            'reportdata' => $this->getReportData(),
        ]);
    }

    public function getReportData()
    {
        $reportdata = null;
        // Mengatur query untuk mengambil data laporan
        $filter = session('filter-report');

        if ($filter) {
            if ($filter['report_type'] == 1) {

                $reportdata = Student::leftJoin('grades', 'grades.id', 'students.grade_id')
    ->selectRaw('grades.grade_name, SUM(students.balance) as total_balance')
    ->groupBy('students.grade_id', 'grades.grade_name') // <--- Tambahkan grades.grade_name di sini
    ->get();

            } else if ($filter['report_type'] == 2) {

                $reportdata = Student::where('grade_id', $filter['grade'])->get();

            } else if ($filter['report_type'] == 3) {

                $reportdata = Transaction::leftJoin('students', 'transactions.student_id', 'students.id')
                    ->leftJoin('grades', 'students.grade_id', 'grades.id')
                    ->whereBetween('transaction_date', [$filter['start_date'], $filter['end_date']])
                    ->selectRaw('students.name, grades.grade_name, transactions.*')
                    ->selectRaw("CASE transaction_type WHEN 'in' THEN amount ELSE 0 END AS deposit")
                    ->selectRaw("CASE transaction_type WHEN 'out' THEN amount ELSE 0 END AS withdrawal")
                    ->get();

            } else if ($filter['report_type'] == 4) {

                $reportdata = Transaction::leftJoin('students', 'transactions.student_id', 'students.id')
                    ->leftJoin('grades', 'students.grade_id', 'grades.id')
                    ->where('student_id', $filter['student'])
                    ->whereBetween('transaction_date', [$filter['start_date'], $filter['end_date']])
                    ->selectRaw('students.name, grades.grade_name, transactions.*')
                    ->selectRaw("CASE transaction_type WHEN 'in' THEN amount ELSE 0 END AS deposit")
                    ->selectRaw("CASE transaction_type WHEN 'out' THEN amount ELSE 0 END AS withdrawal")
                    ->get();
            }

            // Mengatur format data pada kolom tabel
            if ($reportdata) {
                foreach ($reportdata as $data) {
                    if ($filter['report_type'] == 1) {
                        $data->total_balance = 'Rp. ' . number_format($data->total_balance, 0, ',', '.');
                    } elseif ($filter['report_type'] == 2) {
                        $data->balance = 'Rp. ' . number_format($data->balance, 0, ',', '.');
                    } else {
                        $data->transaction_date = Carbon::parse($data->transaction_date)->isoFormat('D MMMM Y');
                        $data->deposit = 'Rp. ' . number_format($data->deposit, 0, ',', '.');
                        $data->withdrawal = 'Rp. ' . number_format($data->withdrawal, 0, ',', '.');
                        $data->balance = 'Rp. ' . number_format($data->balance, 0, ',', '.');
                    }
                }
            }
        }

        return $reportdata;
    }

    // Mengambil data siswa sesuai kelas
    public function getStudent(Request $request)
    {
        return back()->with(['grade' => $request->grade]);
    }

    // Menyimpan filter pada session
    public function store(Request $request)
    {
        session(['filter-report' => [
            'report_type' => $request->report_type,
            'grade' => $request->grade,
            'student' => $request->student,
            'start_date' => $request->start_date,
            'end_date' => Carbon::parse($request->end_date)->addDays(1),
        ]]);

        return back();
    }

    // Download file laporan PDF
    public function downloadPdf($type)
    {
        if ($type == 1) $view = 'pdf.report-one';
        if ($type == 2) $view = 'pdf.report-two';
        if ($type == 3) $view = 'pdf.report-three';
        if ($type == 4) $view = 'pdf.report-four';

        $filter = session('filter-report');
        $data = [
            'reportdata' => $this->getReportData(),
            'grade' => Grade::find($filter['grade'] ?? null),
            'student' => Student::find($filter['student'] ?? null),
            'start' => Carbon::parse($filter['start_date'] ?? null)->isoFormat('D MMMM Y'),
            'end' => Carbon::parse($filter['end_date'] ?? null)->isoFormat('D MMMM Y'),
        ];

        $pdf = PDF::loadView($view, $data);
        return $pdf->download('Laporan.pdf');
    }
}
