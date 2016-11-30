<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    //
    protected $table = 'packages';
    protected $fillabel = ['name','description','price'];
    public $timestamps = false;

}
