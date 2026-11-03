<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Grade;

class GradeController extends Controller
{
    // Ambil semua data kelas
    public function index()
    {
        $grades = Grade::orderBy('id', 'desc')->get();
        return Inertia::render('Grade/Index', [
            'grades' => $grades
        ]);
    }

    // Insert data kelas
    public function store(Request $request)
    {
        $request->validate([
            'grade_name' => 'required',
        ]);

        Grade::create([
            'grade_name' => $request->grade_name,
        ]);

        return back();
    }

    // Update data sesuai id
    public function update(Request $request, string $id)
    {
        $request->validate([
            'grade_name' => 'required',
        ]);

        $grade = Grade::findOrFail($id);
        $grade->update([
            'grade_name' => $request->grade_name,
        ]);

        return back();
    }

    // Menghapus data sesuai id
    public function destroy(string $id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return back();
    }
}
