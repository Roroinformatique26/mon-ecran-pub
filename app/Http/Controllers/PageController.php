<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home() {
        return view('home'); // resources/views/home.blade.php
    }

    public function infos() {
        return view('infos'); // resources/views/infos.blade.php
    }
    public function contact() {
        return view('contact'); // resources/views/contact.blade.php
    }
}
