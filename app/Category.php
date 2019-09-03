<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    //Define una relación el el model Post
    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}
