<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected  $table = 'posts';
    
    //relacion de muchos a uno(muchos post son creados por un usuario)
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
     //relacion de muchos a uno(muchos post pertenecen a una categoria)
    public function category()
    {
        return $this->belongsTo('App\Category','category_id');
    }
}
