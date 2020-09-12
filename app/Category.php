<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected  $table='categories';
    
    //relacion de uno a muchos(una categoria tiene muchos posts)
    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}
