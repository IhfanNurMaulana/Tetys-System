<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Grade;
use App\Models\Student;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentFormatExport;
use App\Imports\StudentImport;

class StudentController extends Controller
{
    // Ambil semua data siswa
    public function index(Request $request)
    {
        $grades = Grade::all();
        $students = Student::with('grade');

        // Mengatur sorting tabel
        if ($request->has('sort_by') && $request->has('sort_dir')) {
            $students->orderBy($request->sort_by, $request->sort_dir);
        } else {
            $students->orderBy('id', 'desc');
        }

        // Mengatur filter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $students->where(function ($squery) use ($searchTerm) {
                $squery->where('name', 'like', "%{$searchTerm}%")
                       ->orWhere('place_of_birth', 'like', "%{$searchTerm}%");
            });
        }

        // Mengatur paginasi tabel
        if ($request->has('page') && $request->has('perPage')) {
            $perPage = (int) $request->perPage;
            $start = ((int) $request->page - 1) * $perPage;
            $students = $students->offset($start)->limit($perPage);
        }

        $students = $students->get();

        // Mendapatkan total data siswa
        $total = Student::count();

        // Mengubah tampilan foto dan tanggal lahir
        foreach ($students as $student) {
            $student->photo = str_replace('uploads/', url('/').'/storage/uploads/', $student->photo);
            $student->date_of_birth = Carbon::parse($student->date_of_birth)->isoFormat('D MMMM Y');
        }

        return Inertia::render('Student/Index', [
            'grades' => $grades,
            'students' => $students,
            'total' => $total,
        ]);
    }

    // Insert data siswa
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:50|unique:students,nis',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required',
            'grade_id' => 'required|exists:grades,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $filePath = null;
        //Memproses upload foto
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/photos', $fileName, 'public');
        }

        $student = Student::create([
            'nis' => $request->nis,
            'name' => $request->name,
            'gender' => $request->gender,
            'place_of_birth' => $request->place_of_birth,
            'date_of_birth' => $request->date_of_birth,
            'grade_id' => $request->grade_id,
            'photo' => $filePath ?? null,
        ]);

        return back();
    }

    // Update data sesuai id
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nis' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required',
            'grade_id' => 'required|exists:grades,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student = Student::findOrFail($id);

        //Upload foto
        $filePath = $student->photo;
        if ($request->hasFile('photo')) {
            if ($student->photo && Storage::exists($student->photo)) {
                Storage::delete($student->photo);
            }
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/photos', $fileName, 'public');
        }

        $student->update([
            'nis' => $request->nis,
            'name' => $request->name,
            'gender' => $request->gender,
            'place_of_birth' => $request->place_of_birth,
            'date_of_birth' => $request->date_of_birth,
            'grade_id' => $request->grade_id,
            'photo' => $filePath ?? $student->photo,
        ]);

        return back();
    }

    // Menghapus data sesuai id
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);

        //Hapus foto jika ada
        if ($student->photo && Storage::exists($student->photo)) {
            Storage::delete($student->photo);
        }

        $student->delete();

        return back();
    }

    // Download format import excel
    public function downloadExcel()
    {
        return Excel::download(new StudentFormatExport, 'Format Impor Siswa.xlsx');
    }

    // Import data siswa dari format excel
    public function importExcel(Request $request)
    {
        $request->validate([
            'grade_id' => 'required',
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        Excel::import(new StudentImport($request->grade_id), $request->file('file'));

        return back();
    }
}
