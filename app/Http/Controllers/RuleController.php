<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Storage;

use App\Models\Rule; 
use App\Models\FAQ; 
class RuleController extends Controller
{  public function fetchRules()
    {
        $lastRule = Rule::all();

    if ($lastRule) {
        // Assuming the structure of your $lastRule matches the expected response
        return response()->json($lastRule);
    } else {
        return response()->json(['message' => 'No FAQs found.'], 404);
    }
    }

    public function fetchFAQs()
{
    $faqs = FAQ::all();

    $formattedData = $faqs->map(function ($faq) {
        return [
            'question' => $faq->question,
            'answer' => $faq->answer,
        ];
    });

    // Return the formatted data as a JSON response
    return response()->json($formattedData);
}
    

    public function addRule(Request $request)

    {

        if ($request->hasFile('pdfFile')) {
            $pdfFile = $request->file('pdfFile');
            $filename = time() . '_' . $pdfFile->getClientOriginalName();
            $path = $pdfFile->storeAs('pdf', $filename, 'public'); 
              $rule = new Rule();
            $rule->pdfPath = Storage::url($path); 
            $rule->save();
            return response()->json(['message' => 'File added successfully']);
        }
        else{
            
            $faqs = new FAQ();
            $faqs->question = $request->input('question');
            $faqs->answer = $request->input('answer');
            $faqs->save(); 
             return response()->json(['message' => 'Question and answer added successfully']);

        }

        

    }
 


    public function getPdf($filename)
{
    $path = storage_path('app/public/pdf/' . $filename); 
    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;

}}
