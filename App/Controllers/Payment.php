<?php

namespace App\Controllers;

use \App\Config;
use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class payment extends \Core\Controller
class Payment extends Authenticated
{
	
	/**
     * Before filter - called before each action method
     *
     * @return void
     */
	 protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }
	
	 /**
     * Validate if payment category is available (AJAX) for an user.
     * The ID of an existing payment category can be passed in in the querystring to ignore in settings tab.
     *
     * @return void
     */
    public function validateTypeAction()
    {	
        $is_valid = ! $this->user->paymentTypeNameExists($_GET['paymentTypeNewName'], $_GET['ignore_id'] ?? null);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}