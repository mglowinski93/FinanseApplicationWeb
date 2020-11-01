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
			'income_categories' => $this->user->getIncomeCategories(),
			'default_income_category' => Config::DEFAULT_INCOME_CATEGORY
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
	
	 /**
     * Validate if income category is available (AJAX) for an user.
     * The ID of an existing income category can be passed in in the querystring to ignore in settings tab.
     *
     * @return void
     */
    public function validateCategoryAction()
    {
        $is_valid = ! $this->user->incomeCategoryNameExists($_GET['incomeCategoryNewName'], $_GET['ignore_id'] ?? null);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}