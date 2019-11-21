<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //

    public function index(){
        return view('home');
    }

    public function displayImage($imgUrl){
        return view('show', compact('imgUrl'));
    }

    public function imageList(){
        return view('list');
    }
}
