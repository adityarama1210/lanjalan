<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;
use App\Word;
use App\DocumentWord;

class HomeController extends Controller
{
    //
    public function index(){
    	return view('index');
    }
    public function search(Request $request){
    	$query = $request->input('query');

    	return view('index');
    }
}
