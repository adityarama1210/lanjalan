<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;
use App\Word;
use App\DocumentWord;
use DB;

class HomeController extends Controller
{
    //
    public function index(){
    	return view('index');
    }
    public function search(Request $request){
    	$query = $request->input('query');
        if(!$query){
            return redirect()->route('index');
        }
    	$arr = explode(" ", strtolower($query));
    	$arr = array_count_values($arr);
        $arr_of_query = [];
        $query_weight = 0.0;
        $arr_of_word = array_keys($arr);
        $words = DB::table('words')->whereIn('word', $arr_of_word)->get();
        $words_id = [];
        for($i = 0 ; $i < count($words) ; $i++){
            $words_id[$i] = $words[$i]->id;
        }
        $document_words = DB::table('document_words')->whereIn('word_id',$words_id)->orderBy('package_id')->get();
        $arr_of_similarity = [];
        if(count($document_words) > 0){
            foreach($document_words as $document_word){
                $w = Word::find($document_word->word_id);
                if(array_key_exists($document_word->package_id, $arr_of_similarity)){
                    $result = $arr_of_similarity[$document_word->package_id];
                    $result += ($document_word->weight * $w->idf * $arr[$w->word]);
                }
                else{
                    $w = Word::find($document_word->word_id);
                    $result = ($document_word->weight * $w->idf * $arr[$w->word]);
                }
                $arr_of_similarity[$document_word->package_id] = $result;
            }
            uasort($arr_of_similarity, function($a, $b){
                if($a == $b)
                    return 1;
                else
                    return $b - $a;
            });
            $arr = [];
            foreach($arr_of_similarity as $package_id => $relevance_value){
                array_push($arr, Package::find($package_id));
            }
            return $arr;
        }
        else{
            return redirect()->back()->with('error','No Result Found');
        }
    	/*foreach($arr as $w => $freq){

            if($word){
                $idf = $word->idf;
                $arr_of_query[$w] = $freq * $idf;
            }
    	}*/
    	//return view('index');
    }
}
