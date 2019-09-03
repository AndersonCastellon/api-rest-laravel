<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    private const MODEL_USER = 'App\User';
    private const USER_KEY = 'user_id';
    private const MODEL_CATEGORY = 'App\Category';
    private const CATEGORY_KEY = 'category_id';

    protected $table = 'posts';

    //Relación de muchos a uno
    public function user()
    {
        return $this->belongsTo(self::MODEL_USER,
            self::USER_KEY);
    }

    public function category()
    {
        return $this->belongsTo(self::MODEL_CATEGORY,
            self::CATEGORY_KEY);
    }
}
