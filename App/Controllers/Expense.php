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
class Expense extends Authenticated
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
        View::renderTemplate('Expenses/new.html', [
			'expense_categories' => $this->user->getExpenseCategories(),
			'payment_types' => $this->user->getPaymentTypes(),
			'default_expense_category' => Config::DEFAULT_EXPENSE_CATEGORY,
			'default_payment_category' => Config::DEFAULT_PAYMENT_CATEGORY
		]);
    }

    /**
     * Save income in database
     *
     * @return void
     */
    public function saveAction()
    {
        $this->user->saveExpense($_POST);
		Flash::addMessage('Expense successfully saved');
		$this->redirect('/expense/new');
	}
	
	/**
     * Validate if expense category is available (AJAX) for an user.
     * The ID of an existing expense category can be passed in in the querystring to ignore in settings tab.
     *
     * @return void
     */
    public function validateCategoryAction()
    {
        $is_valid = ! $this->user->expenseCategoryNameExists($_GET['expenseCategoryNewName'], $_GET['ignore_id'] ?? null);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}