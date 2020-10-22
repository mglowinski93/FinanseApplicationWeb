<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */
//class Income extends \Core\Controller
class Income extends Authenticated
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
     * Items index
     *
     * @return void
     */
    public function newAction()
    {
		
        View::renderTemplate('Incomes/new.html', [
			'income_categories' => $this->user->getIncomeCategories()
		]);
    }

    /**
     * Save income in database
     *
     * @return void
     */
    public function saveAction()
    {
        $this->user->saveIncome($_POST);
		Flash::addMessage('Income successfully saved');
		$this->redirect('/income/new');
	}
}