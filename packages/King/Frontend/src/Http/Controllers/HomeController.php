<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class HomeController extends FrontController
{
    public function index()
    {
        return view('frontend::home.index');
    }

    /**
     * @todo Search city by name
     *
     * @param Illuminate\Http\Request $request
     *
     * @return JSON
     */
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

    public function ajaxSelectLocation(Request $request, $id) {
        //Only accept ajax request
        if ($request->ajax()) {
            $id = (int) $id;
            if (City::find($id) !== null) {
                $request->session()->put(_const('SESSION_LOCATION'), $id);

                return ajax_response([
                    'status' => _const('AJAX_OK'),
                    'data'   => _t('saved_info')
                ]);
            }
        }
    }
}