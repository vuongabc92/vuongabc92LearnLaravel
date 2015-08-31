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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxSearchLocation(Request $request) {

        //Only accept ajax request
        if ($request->ajax()) {

            $city_name = $request->get('location_keyword');
            $results   = locations(trim($city_name));

            return pong(1, ['data' => $results]);
        }
    }

    /**
     * @todo Save location by session
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id City id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxSelectLocation(Request $request, $id) {

        //Only accept ajax request
        if ($request->ajax()) {

            $id = (int) $id;

            if (City::find($id) !== null) {

                //Start session if it wasn't started
                if ( ! $request->session()->isStarted()) {
                    $request->session()->start();
                }

                $request->session()->put(_const('SESSION_LOCATION'), $id);

                return pong(1, _t('saved_info'));
            }
        }
    }
}