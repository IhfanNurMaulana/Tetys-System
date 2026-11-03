<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // Ambil semua data siswa
    public function index(Request $request)
    {
        $grades = Grade::all();
        $transactions = Transaction::with('student', 'student.grade');

        // Menangani sorting kolom pada tabel
        if ($request->has('sort_by') && $request->has('sort_dir')) {
            $transactions->orderBy($request->sort_by, $request->sort_dir);
        } else {
            $transactions->orderBy('id', 'desc');
        }

        // Menangani pencarian pada tabel
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $transactions->where(function ($query) use ($searchTerm) {
                $query->where('student.name', 'like', "%{$searchTerm}%")
                      ->orWhere('student.grade.grade_name', 'like', "%{$searchTerm}%");
            });
        }

        // Menangani data tiap halaman tabel
        if ($request->has('page') && $request->has('perPage')) {
            $perPage = (int) $request->perPage;
            $start = ((int) $request->page - 1) * $perPage;
            $transactions = $transactions->offset($start)->limit($perPage);
        }

        // Menyelesaikan query dan mendapatkan jumlah item
        $transactions = $transactions->get();
        $total = Transaction::count();

        // Memperbarui data siswa ketika kelas dipilih
        $students = null;
        $grade = 0;
        if (session('grade')) {
            $grade = session('grade');
            $students = Student::where('grade_id', $grade)->get();
        }

        // Mengatur format tampilan data
        foreach ($transactions as $trans) {
            $trans->transaction_date = Carbon::parse($trans->transaction_date)->isoFormat('D MMMM Y');
            $trans->transaction_type = ($trans->transaction_type == 'in') ? 'Setoran' : 'Penarikan';
            $trans->amount = 'Rp. ' . number_format($trans->amount, 0, ',', '.');
            $trans->balance = 'Rp. ' . number_format($trans->balance, 0, ',', '.');
        }

        return Inertia::render('Transaction/Index', [
            'grade' => $grade,
            'grades' => $grades,
            'transactions' => $transactions,
            'total' => $total,
            'students' => Inertia::lazy(fn () => $students),
        ]);
    }

    // Mengambil data siswa sesuai kelas
    public function getStudent(Request $request)
    {
        return back()->with(['grade' => $request->grade]);
    }

    // Insert data transaksi
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'transaction_type' => 'required|in:in,out',
            'amount' => 'required',
            'description' => 'required',
        ]);

        $student = Student::find($request->student_id);

        // Menghilangkan titik pada input mata uang
        $amount = str_replace(".", "", $request->amount);

        // Memperbarui saldo siswa
        if ($request->transaction_type == 'in') {
            $student->balance += $amount;
        } else {
            // Menampilkan pesan jika saldo tidak cukup
            if ($student->balance < $amount) {
                $balance = 'Rp. ' . number_format($student->balance, 0, ',', '.');
                return back()->withErrors([
                    'amount' => 'Saldo tinggal ' . $balance . ' tidak cukup untuk melakukan transaksi ini.'
                ]);
            } else {
                $student->balance -= $amount;
            }
        }

        // Menyimpan transaksi
        $transaction = Transaction::create([
            'student_id' => $request->student_id,
            'transaction_date' => date('Y-m-d'),
            'transaction_type' => $request->transaction_type,
            'amount' => $amount,
            'balance' => $student->balance,
            'description' => $request->description,
        ]);

        if ($transaction) $student->update();

        return back();
    }
}
