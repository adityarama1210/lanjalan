<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    //
    protected $table = 'words';
    protected $fillabel = ['word'];
    public $timestamps = false;
}
