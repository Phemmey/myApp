<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        $title = "Laravel welcomes you!";
        return view('pages.index', compact('title'));
    }
    
      public function about(){
          $title="About us";
        return view('pages.about') ->with('title', $title);
    }
    
    
      public function services(){
          $data =array(
              'title'=> 'Services',
              'Services'=>['Home Service', 'Cathering', 'Much more']
          );
        return view('pages.services')->with($data);//cnnot use compact her since its an array
    }
    
}
