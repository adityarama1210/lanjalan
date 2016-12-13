<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Package;
use App\Word;
use App\DocumentWord;
use DB;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    public function index() {
    	return view('index', ['search' => false]);
    }

    public function search(Request $request) {
        $query = $request->input('query');
        $min = $request->input('min');
        $max = $request->input('max');
        $arr = $this->search_by_string($query, $min, $max);
        if ($arr) {
            return view('index', ['search' => true, 'data' => $arr, 'error' => false, 'randomimage' => ['http://placehold.it/300x300']]);
        } else {
            return view('index', ['search' => true, 'data' => [], 'error' => true, 'randomimage' => []]);
        }
    }

    public function search_by_string($query, $min=-1, $max=-1){
        if(!$query){
            return redirect()->route('index');
        }
        $querySplit = explode(" ", strtolower($query));
        $cleanQuery = [];
        foreach ($querySplit as $token) {
            if($token != "") {
                array_push($cleanQuery, $token);
            }
        }
        $arr = array_count_values($cleanQuery);
        $document_words = $this->get_document_words($arr);
        if(count($document_words) > 0) {
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
                    // Only add the word if it's not present in the query
                    $word = Word::find($w->word_id)->word;
                    if(!in_array($word, $cleanQuery) and !in_array($word, $arr_of_expansion_words)){
                        array_push($arr_of_expansion_words, $word);
                        break;
                    }
                }
            }
            $cleanQuery = array_merge($cleanQuery, $arr_of_expansion_words);

            $arr = array_count_values($cleanQuery);
            $document_words = $this->get_document_words($arr);
            $arr_of_similarity = [];
            $arr_of_similarity = $this->get_array_of_similarity($document_words, $arr_of_similarity, $arr);
            
            //print_r($arr_of_similarity);
            $arr = [];
            foreach($arr_of_similarity as $package_id => $relevance_value){
                if ($min > -1) {
                    $package = Package::find($package_id);
                    $price_range = str_replace(".", "", $package->price);
                    $price_range = explode(' - ', $price_range);

                    if (intval($price_range[0]) >= $min && intval($price_range[1]) <= $max) {
                        array_push($arr, $package);
                    }
                } else {
                    array_push($arr, Package::find($package_id));
                }
            }
            //return view('index', ['search' => true, 'data' => $arr, 'error' => false, 'randomimage' => ['http://placehold.it/300x300']]);
            //return $arr;
        }
        else{
            //return view('index', ['search' => true, 'data' => [], 'error' => true, 'randomimage' => []]);
            $arr = [];
            //return $arr;
        }
        return $arr;
        //return $min;
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
        // ### GEOGRAPHIC IR
        $error = false;
        $recommendation = [];
        // # GeoParsing
        $thePackage = Package::find($id);
        $token = explode(" ", strtolower($thePackage->name));
        $path = app_path('Lokasi.json');
        $file = \File::get($path);
        $countries = json_decode($file)->countries;
        $provinsi = json_decode($file)->provinsi;
        $kabupaten = json_decode($file)->kabupaten;
        $assocLokasi = [];
        foreach($countries as $lokasi) {
            $locName = strtolower($lokasi->name);
            $locNameSplit = explode(" ", $locName);
            if(count($locNameSplit) > 1) {
                if(!isset($assocLokasi[$locNameSplit[0]])) {
                    $assocLokasi[$locNameSplit[0]] = [];
                }
                $assocLokasi[$locNameSplit[0]][$locNameSplit[1]] = [];
            } else {
                if(!isset($assocLokasi[$locNameSplit[0]])) {
                    $assocLokasi[$locNameSplit[0]] = [];
                }
            }
        }
        foreach($provinsi as $lokasi) {
            $locName = strtolower($lokasi->name);
            $locNameSplit = explode(" ", $locName);
            if(count($locNameSplit) > 1) {
                if(!isset($assocLokasi[$locNameSplit[0]])) {
                    $assocLokasi[$locNameSplit[0]] = [];
                }
                $assocLokasi[$locNameSplit[0]][$locNameSplit[1]] = [];
            } else {
                if(!isset($assocLokasi[$locNameSplit[0]])) {
                    $assocLokasi[$locNameSplit[0]] = [];
                }
            }
        }
        foreach($kabupaten as $lokasi) {
            $locName = strtolower($lokasi->name);
            $prefix = "kabupaten ";
            if (substr($locName, 0, strlen($prefix)) == $prefix) {
                $locName = substr($locName, strlen($prefix));
            }
            $prefix = "kota ";
            if (substr($locName, 0, strlen($prefix)) == $prefix) {
                $locName = substr($locName, strlen($prefix));
            }
            $locNameSplit = explode(" ", $locName);
            if(count($locNameSplit) > 1) {
                if(!isset($assocLokasi[$locNameSplit[0]])) {
                    $assocLokasi[$locNameSplit[0]] = [];
                }

                $assocLokasi[$locNameSplit[0]][$locNameSplit[1]] = [];
            } else {
                if(!isset($assocLokasi[$locNameSplit[0]])) {
                    $assocLokasi[$locNameSplit[0]] = [];
                }
            }
        }

        $centroid = "";
        for ($i=0; $i < count($token); $i++) {
            $word = $token[$i];
            if($i+1 < count($token)) {
                $nextWord = $token[$i+1];
            }
            if(isset($assocLokasi[$word])) {
                $centroid .= $word;
                if(count($assocLokasi[$word]) > 0) {
                    if(isset($assocLokasi[$word][$nextWord])) {
                        $centroid .= "+" . $nextWord;
                    }
                }
            }
        }

        // # GeoCoding
        if($centroid != "") {
            $latlng = $this->getLatLong($centroid);
            if(count($latlng) > 0) {
                // # NearbyCities
                $nearbyCities = $this->getNearbyCities($latlng, 100);
                if(count($nearbyCities) > 0) {
                    // SEARCH QUERY with 3 nearby cities in query, get 4 best result
                    $query = "";
                    $numberOfCityToQuery = 0;
                    $numberOfRecommendation = 0;
                    foreach ($nearbyCities as $city) {
                        if($numberOfCityToQuery < 3) {
                            $query .= $city;
                            if($numberOfCityToQuery != 2) {
                                $query .= " ";
                            }
                            $numberOfCityToQuery++;
                        }
                    }
                    
                    $searchResult = $this->search_by_string($query);
                    if(count($searchResult) > 0) {
                        foreach ($searchResult as $package) {
                            if($numberOfRecommendation < 4) {
                                if($package->id != $id) {
                                    array_push($recommendation, $package);
                                    $numberOfRecommendation++;
                                }
                            }
                        }
                        // echo("Location: ". $centroid);
                        // echo("<br/>Query: ". $query);
                        // echo("<br/>Recommendation: ");
                        // dd($recommendation);
                    } else {
                        $error = "Package for recommendation not found";
                    }
                } else {
                    $error = "Unable to get nearby cities";
                }
            } else {
                $error = "Unable to get latitude and longitude";
            }
        } else {
            $error = "Location not recognized";
        }

        $payload = ['package' => $thePackage,
        'recommendation' => $recommendation,
        'randomimage' => ['http://placehold.it/300x300'],
        'error' => $error
        ];
        return view('package', $payload);
    }

    private function getNearbyCities($latlng, $radius) {
        $client = new Client(['timeout'  => 10.0]);
        $uri = 'http://gd.geobytes.com/GetNearbyCities?callback=?&radius='.$radius.'&Latitude='.$latlng->lat.'&Longitude='.$latlng->lng;
        $response = $client->request('GET', $uri);
        if($response->getStatusCode() == 200) {
            $body = substr($response->getBody()->getContents(), 2);
            $body = substr($body, 0, strlen($body)-2);
            $nearbyCities = json_decode($body);
            $nearbyCityNames = [];
            if(count($nearbyCities) > 0 && count($nearbyCities[0]) > 0) {
                foreach($nearbyCities as $nearbyCity) {
                    array_push($nearbyCityNames, $nearbyCity[1]);
                }
                return $nearbyCityNames;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    private function getLatLong($city) {
        $client = new Client(['timeout'  => 10.0]);
        $uri = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$city.'&key=AIzaSyDQw1i5TYajVY1RDAFgUT0RgNaYxHX5FQw';
        $response = $client->request('GET', $uri);
        if($response->getStatusCode() == 200) {
            $body = json_decode($response->getBody()->getContents());
            if($body->status == "OK") {
                $latlng = $body->results[0]->geometry->location;
                return $latlng;
            }
        } else {
            return [];

        }
    }
}
