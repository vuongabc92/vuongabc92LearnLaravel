<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends FrontController
{
    public function index()
    {
        return view('frontend::store.index');
    }
}