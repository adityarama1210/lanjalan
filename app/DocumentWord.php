<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentWord extends Model
{
    //
    protected $table = 'document_words';
    protected $fillabel = ['package_id','word_id','weight'];
    public $timestamps = false;
}
