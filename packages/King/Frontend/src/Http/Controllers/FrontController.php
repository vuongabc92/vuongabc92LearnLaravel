<?php

/**
 * FrontendController
 *
 * @author vuongabc92@gmail.com
 */

namespace King\Frontend\Http\Controllers;

use App\Http\Controllers\Controller;

class FrontController extends Controller{
    
    /**
     * Bind form data into the present entity
     * 
     * @param object $entity Some entity
     * @param array  $form Form data
     * 
     * @return object
     */
    public function bind($entity, $form, $except = array('_token', 'id'))
    {
        if (count($form)) {
            foreach ($form as $k => $v) {
                if ( ! in_array($k, $except)) {
                    $entity->$k = ($k === 'password') ? bcrypt($v) : $v;
                }
            }
        }
        
        return $entity;
    }
}
