<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

class HomeController extends FrontController
{
    public function index()
    {
        return view('frontend::home.index');
    }
}