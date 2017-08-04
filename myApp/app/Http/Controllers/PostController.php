<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;


class PostController extends Controller
{
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // $posts = Post::orderBy('title','desc')->take(1)->get();
         
       // $posts = Post::all();
      //  $posts = Post::orderBy('title','desc')->get();
         $posts = Post::orderBy('created_at','desc')->paginate(2);
        return view('posts.index')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate( $request,[
            'title' =>'required',
             'body' =>'required',
             'cover_image' => 'image|nullable|max:1999'

        ]);
        //handling file uploading
        if($request->hasFile('cover_image')){
            //get the filename with its extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //get just filename
            $filename= pathinfo($filenameWithExt,PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $filenameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);
        }else{
            $filenameToStore = 'noimage.jpg'; 
        }

        //create post
            $post= new post;
            $post->title=$request->input('title');
            $post->body=$request->input('body');
            $post->cover_image=$filenameToStore;
             $post->user_id=auth()->user()->id;
            $post->save();
         return  redirect('/posts')->with('success','Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $posts = Post::find($id);
        return view('posts.show')->with('post',$posts);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $posts = Post::find($id);
         //Check for logged in user
         if(auth()->user()->id!==$posts->user_id){
            return redirect('/posts')->with('error','Access Denied');
         }
        return view('posts.edit')->with('post',$posts);
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
        {
        $this->validate( $request,[
            'title' =>'required',
             'body' =>'required'

        ]);
         //handling file uploading
        if($request->hasFile('cover_image')){
            //get the filename with its extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //get just filename
            $filename= pathinfo($filenameWithExt,PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $filenameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $filenameToStore);
        }
        //create post
            $post = Post::find($id);
            $post->title = $request->input('title');
            $post->body=$request->input('body');
            if($request->hasFile('cover_image')){
                $post->cover_image = $filenameToStore;
            }
            $post->save();
         return  redirect('/posts')->with('success','Post Updated');
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {    $post= Post::find($id);
             //Check for logged in user
         if(auth()->user()->id!==$post->user_id){
            return redirect('/posts')->with('error','Access Denied');
         }
         if ($post->cover_image != 'noimage.jpg'){
             //delete the image
             Storage::delete('public/cover_images/'.$post->cover_image);
         }
          $post->delete();
         return  redirect('/posts')->with('success','Post Deleted');
    }
}
