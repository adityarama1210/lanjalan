<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Idf extends Model
{
    //
    protected $table = 'idfs';
    protected $fillabel = ['word_id','idf'];
    public $timestamps = false;
}
