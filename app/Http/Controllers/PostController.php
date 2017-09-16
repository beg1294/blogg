<?php

namespace App\Http\Controllers;

use App\Category;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Post;
use Illuminate\Support\Facades\Session;
use Purifier;
use Image;
use Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $posts=Post::orderBy('id','desc')->simplePaginate(10);
        return view('posts.index',['posts'=>$posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create')->withCategories($categories)->withTags($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate the requeast
        $this->validate($request,array(
            'title'       =>    'required|max:255',
            'slug'        =>    'required|alpha_dash|min:5|max:255|unique:posts,slug',
            'category_id' =>    'required|integer',
            'body'        =>    'required',
            'featured_image' => 'sometimes|image'
            ));
        //store in the database
        $post = new Post;

        $post->title         =  $request->title;
        $post->slug          =  $request->slug;
        $post->category_id   =  $request->category_id;
        $post->body          =  Purifier::clean($request->body);

        if($request->hasFile('featured_image'))
        {
            $image = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(800, 400)->save($location);

            $post->image = $filename;
        }

        $post->save();
        $post->tag()->sync($request->tags,false);
        Session::flash('success','The blog post was successfully saved');
        return redirect()->route('posts.show',$post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show',['post'=>$post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post=Post::find($id);
        $categories = Category::all();
        $cats = [];
        foreach($categories as $category)
        {
            $cats[$category->id] = $category->name;
        }

        $tags = Tag::all();
        $tags2 = [];

        foreach($tags as $tag)
        {
            $tags2[$tag->id] = $tag->name;
        }

        return view('posts.edit',['post'=>$post,'categories'=>$cats])->withTags($tags2);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        $this->validate($request,array(
            'title'          =>'required|max:255',
            'category_id'    => 'required|integer',
            'slug'           => "required|alpha_dash|min:5|max:255|unique:posts,slug,$id",
            'body'           =>'required',
            'featured_image' => 'sometimes|image'
        ));



        $post->title = $request->input('title');
        $post->slug = $request->input('slug');
        $post->category_id = $request->input('category_id');
        $post->body = Purifier::clean($request->input('body'));

        if($request->hasFile('featured_image'))
        {
            $image = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(800, 400)->save($location);
            $oldFilename = $post->image;
            //update the database
            $post->image = $filename;
            //Delete the old photo
            Storage::delete($oldFilename);
        }

        $post->save();

        $post->tag()->sync($request->tags);

        Session::flash('success','This post was successfully saved');
        return redirect()->route('posts.show',$post->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post=Post::find($id);
        $post->tag()->detach();
        Storage::delete($post->image);
        $post->delete();
        Session::flash('success','The post was successfully deleted.');
        return redirect()->route('posts.index');
    }


}
