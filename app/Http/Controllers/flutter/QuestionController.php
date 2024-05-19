<?php

namespace App\Http\Controllers\flutter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survey;

class QuestionController extends Controller
{
    public function show()
{
    try {
        $surveys = Survey::all();

        return response()->json($surveys, 200);
    } catch (\Exception $e) {
        // Log the error message
        Log::error('Failed to fetch surveys.', ['error' => $e->getMessage()]);

        return response()->json(['message' => 'Failed to fetch surveys'], 500);
    }
}



public function store(Request $request)
{
    $answers = $request->all();

    foreach ($answers as $answer) {
        $questionText = $answer['question'];
        $response = $answer['answer'];

        // Find the survey question by its text
        $survey = Survey::where('questions', $questionText)->first();

        if ($survey) {
            // Update the appropriate column based on the response
            switch ($response) {
                case 'Baik':
                    $survey->baik += 1;
                    break;
                case 'Sangat Baik':
                    $survey->sangat_baik += 1;
                    break;
                case 'Buruk':
                    $survey->buruk += 1;
                    break;
                case 'Sangat Buruk':
                    $survey->sangat_buruk += 1;
                    break;
            }
            $survey->save();
        }
    }

    return response()->json(['message' => 'Responses recorded successfully.'], 200);
}

}
