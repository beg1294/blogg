<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
class BlogController extends Controller
{
    public function getIndex(){
        $posts = Post::paginate(2);
        return view('blog.index',['posts'=>$posts]);
    }
    public function getSingle($slug) {
        $post = Post::where('slug', '=',  $slug)->first();
        return view('blog.single',['post'=>$post]);
    }
}
