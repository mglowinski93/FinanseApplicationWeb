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
	
	/**
     * Validate if limit is not exceeded within this category
     *
     * @return void
     */
    public function validateLimitAction()
    {	
		$categoryId = $_POST['expenseCategoryId'];
		$category_limit = $this->user->getExpenseCategoryLimit($categoryId);
		$current_expense_value = $_POST['expenseValue'];
		if(empty($current_expense_value))
		{
			$current_expense_value = 0;
		}
		
		$startDate = date('Y-m-01');
		$endDate = date("Y-m-t");
		$expense_category = $this->user->getExpenseCategoryLimit($categoryId);
        $expenses_in_current_month_for_category = $this->user->getExpenseCategorySum($categoryId, $startDate, $endDate);
		$difference = $expense_category['expense_category_limit'] - $current_expense_value;

		if($expenses_in_current_month_for_category)
		{
			$difference = $difference - $expenses_in_current_month_for_category[1];
		}
		
		$response = array('limit_enabled' => (bool)$expense_category['limit_enabled'], 'amount_left_to_limit'=> $difference);
		
		header('Content-Type: application/json');
        echo json_encode($response);
    }
}