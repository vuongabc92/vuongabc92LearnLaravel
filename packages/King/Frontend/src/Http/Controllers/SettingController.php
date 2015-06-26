<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers; 

class SettingController extends FrontController{
    
    public function index(){
        return view('frontend::setting.account');
    }
}
