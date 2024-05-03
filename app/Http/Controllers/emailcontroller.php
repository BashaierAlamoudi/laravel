<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Test;
class emailcontroller extends Controller
{
    public function sendTestEmail(){
        $toEmail='gadahAlmuaikel@gmail.com';
        $mm='welocme';
        $data=[
        'mm'=>"try this message",
        'path'=>'public/pdf/1714710533_poster.pdf'    
        ];
        $response=Mail::to($toEmail)->send(new Test($data));
        dd($response);
    }

}
