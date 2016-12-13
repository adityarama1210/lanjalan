<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;
use App\Word;
use App\DocumentWord;
use DB;

class HomeController extends Controller
{
    public function index(){
    	return view('index', ['search' => false]);
    }

    public function search(Request $request){
    	$query = $request->input('query');
        if(!$query){
            return redirect()->route('index');
        }
        $arr1 = explode(" ", strtolower($query));
        $arr = array_count_values($arr1);
        $document_words = $this->get_document_words($arr);
        if(count($document_words) > 0){
            $arr_of_similarity = [];
            
            $arr_of_similarity = $this->get_array_of_similarity($document_words, $arr_of_similarity, $arr);
            
            // Query Expansion
            $temp = array_keys($arr_of_similarity);
            
            // Take three best packages
            $cnt = 0;
            $exp_pack = [];
            foreach ($temp as $doc_id){
                if($cnt == 3){
                    break;
                }
                array_push($exp_pack, $doc_id);
                $cnt++;
            }

            
            $arr_of_expansion_words = [];
            foreach ($exp_pack as $package_id){
                // take 1 top weighted word for this document
                $words = DocumentWord::where('package_id', $package_id)->orderBy('weight','desc')->get();
                foreach ($words as $w){
                    // Only add the word if it's not presence in the query
                    $word = Word::find($w->word_id)->word;
                    if(!in_array($word, $arr1)){
                        array_push($arr_of_expansion_words, $word);
                        break;
                    }
                }
            }
            $arr1 = array_merge($arr1, $arr_of_expansion_words);
             

            $arr = array_count_values($arr1);
            $document_words = $this->get_document_words($arr);
            $arr_of_similarity = [];
            $arr_of_similarity = $this->get_array_of_similarity($document_words, $arr_of_similarity, $arr);
            
            //print_r($arr_of_similarity);
            $arr = [];
            foreach($arr_of_similarity as $package_id => $relevance_value){
                array_push($arr, Package::find($package_id));
            }
            return view('index', ['search' => true, 'data' => $arr, 'error' => false, 'randomimage' => ['http://placehold.it/300x300']]);
        }
        else{
            return view('index', ['search' => true, 'data' => [], 'error' => true, 'randomimage' => []]);
        }
    	
    }
    private function get_document_words($arr){
        $arr_of_query = [];
        $query_weight = 0.0;
        $arr_of_word = array_keys($arr);
        $words = DB::table('words')->whereIn('word', $arr_of_word)->get();
        $words_id = [];
        for($i = 0 ; $i < count($words) ; $i++){
            $words_id[$i] = $words[$i]->id;
        }
        $document_words = DB::table('document_words')->whereIn('word_id',$words_id)->orderBy('package_id')->get();
        return $document_words;
    }
    private function get_array_of_similarity($document_words, $arr_of_similarity, $arr){
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
        return $arr_of_similarity;
    }
    public function package(Request $request, $id) {
        $package = Package::find($id);
        $payload = ['data' => $package, 'randomimage' => ['http://placehold.it/300x300']];
        return view('package', $payload);
    }
}
