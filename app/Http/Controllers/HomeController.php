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
    	$arr = explode(" ", $query);
    	$arr = array_count_values($arr);
        $query_weight = 0.0;
    	foreach($arr as $w => $freq){
            $word = Word::where('word',$w)->first();
            if($word){
                $idf = $word->idf;
                $query_weight += $freq * $idf;
            }
    	}
    	return $query_weight;
    	//return view('index');
    }
}
