<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rule; 
class RuleController extends Controller
{
    public function fetchData()
    {
        // Fetch all question and answer from the Rule model
        $rules =  Rule::all();  
        $formattedRules =array();

        foreach ($rules as $rule) {
            $formattedRules[] = [
                'id' => $rule->id,
                'question' => $rule->question,
                'answer' => $rule->answer,
            ];
        }

        // Return the question and answer as a response (you can customize this)
        return response()->json($formattedRules);
    }
}
