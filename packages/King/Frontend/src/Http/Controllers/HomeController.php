<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends FrontController
{
    public function index()
    {
        return view('frontend::home.index');
    }

    public function ajaxSearchLocation(Request $request) {

        //Only accept ajax request
        if ($request->ajax()) {
            $city_name = $request->get('location_keyword');
            $results   = locations(trim($city_name));

            return ajax_response([
                'status' => _const('AJAX_OK'),
                'data'   => $results
            ]);
        }
    }
}