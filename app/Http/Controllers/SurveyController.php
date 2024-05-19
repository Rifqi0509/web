<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SurveyExport;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::orderBy('created_at', 'desc')->paginate(5);
        return view ('view.questions', compact('surveys'));
    }

    public function create()
    {
        return view('survey.create');
    }

    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'questions' => 'required|string|max:255',
            // tambahkan validasi untuk field lainnya sesuai kebutuhan
        ]);

        // Buat model instance dan isi dengan data yang divalidasi
        $surveys = new Survey(); // Ganti dengan nama model yang sesuai
        $surveys->questions= $validatedData['questions'];
        $surveys->baik = $request->input('baik', null);
        $surveys->sangat_baik = $request->input('sangat_baik', null);
        $surveys->buruk = $request->input('buruk', null);
        $surveys->sangat_buruk = $request->input('sangat_buruk', null);

        // Simpan survey ke database
        $surveys->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('survey.index')->with('success', 'Data berhasil disimpan!');
    }

    public function edit(string $id)
    {
        $survey = Survey::findOrFail($id); 
        return view('survey.edit', compact('survey'));
    }

    public function update(Request $request, string $id)
    {   
        $validatedData = $request->validate([
            'questions' => 'required|string|max:255',
        ]);

        $survey = Survey::findOrFail($id);

        $survey->questions = $validatedData['questions'];
    
        $survey->save();

        return redirect()->route('survey.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();
        return redirect()->route('survey.index')->with('success', 'Data berhasil dihapus!');
    }


    public function cetak(){
        $surveys = Survey::all();
        return view ('rekap.cetak-survey', compact('surveys'));
    }

    public function xlsx()
    {
        return Excel::download(new SurveyExport, 'survey_questions.xlsx');
    }

    public function survey(){
        $survey = Survey::all();
        return view ('auth.survey', compact('survey'));
    }
}
