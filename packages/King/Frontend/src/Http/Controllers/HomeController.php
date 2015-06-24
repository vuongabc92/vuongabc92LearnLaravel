<?php

namespace King\Frontend\Http\Controllers;

class HomeController extends FrontController
{
    public function index()
    {
        auth()->logout();
        return view('frontend::home.index');
    }
}