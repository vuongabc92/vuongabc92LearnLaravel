<?php

namespace King\Frontend\Http\Controllers;

class HomeController extends FrontController
{
    public function index()
    {
        return view('frontend::home.index');
    }
}