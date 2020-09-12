<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;

class PruebaController extends Controller
{
    //
public function testOrm()
    {   //select * from posts
        $posts=Post::all();
        
        $cat=Category::all();
        //recorremos el arreglo de categorias
        foreach($cat as $category)
        {   // obtenemos el campo name de una categoria
            echo "<h1>".$category->name.": </h1>";
                   //recorremos los post de una categoria
                foreach($category->posts as $post)
                {   // obtenemos el campo title
                    echo "<h3>".$post->title."</h3>";
                    // obtenemos el nombre del usuario
                   echo "<h5>".$post->user->name."</h5>";
                   // obtenemos el contenido del post
                   echo "<p>".$post->content."</p>";
                }
        }
        
       /*$posts=Post::all();
        var_dump($posts);*/
        die();// corta la ejecucion
    }
}
