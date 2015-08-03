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
     * Password field name
     *
     * @var string
     */
    protected $_passwordField = 'password';

    /**
     * Bind form data into the present entity
     *
     * @param object $entity Object to bind data into
     * @param array  $form   Form data
     * @param array  $except The data wont be bind
     *
     * @return object
     */
    public function bind($entity, $form, $except = array('_token', 'id')) {

        if (count($form)) {
            foreach ($form as $k => $v) {
                if ( ! in_array($k, $except)) {
                    $entity->$k = ($k === $this->_passwordField) ? bcrypt($v) : $v;
                }
            }
        }

        return $entity;
    }
}
