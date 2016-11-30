<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentWord extends Model
{
    //
    protected $table = 'document_words';
    protected $fillabel = ['doc_id','word_id','weight'];
    public $timestamps = false;
}
