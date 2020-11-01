<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class Profile extends Authenticated
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
     * Show the profile
     *
     * @return void
     */
    public function showAction()
    {
        View::renderTemplate('Profile/show.html', [
            'user' => $this->user,
			'income_categories' => $this->user->getIncomeCategories(),
			'expense_categories' => $this->user->getExpenseCategories()
        ]);
    }

    /**
     * Show the form for editing the profile
     *
     * @return void
     */
    public function editProfileAction()
    {
        View::renderTemplate('Profile/edit_profile.html', [
            'user' => $this->user
        ]);
    }
	
	/**
     * Show the form for editing the income category
     *
     * @return void
     */
    public function editIncomeCategoryAction()
    {
        View::renderTemplate('Profile/edit_income_category.html', [
            'income' => $this->user->getIncomeCategoryById($_POST['incomeCategoryIdToEdit'])
        ]);
    }
	
	/**
     * Update the income category
     *
     * @return void
     */
    public function incomeCategoryUpdateAction()
    {
        if ($this->user->updateIncomeCategory($_POST)) {

            Flash::addMessage('Changes saved');

        } else {
			Flash::addMessage('Failed to save data', Flash::INFO);
        }
		$this->redirect('/profile/show');
    }
	
	/**
     * Show the form for editing the expense category
     *
     * @return void
     */
    public function editExpenseCategoryAction()
    {
        View::renderTemplate('Profile/edit_expense_category.html', [
            'expense' => $this->user->getExpenseCategoryById($_POST['expenseCategoryIdToEdit'])
        ]);
    }
	
	/**
     * Update the expense category
     *
     * @return void
     */
    public function expenseCategoryUpdateAction()
    {
        if ($this->user->updateExpenseCategory($_POST)) {
			
            Flash::addMessage('Changes saved');

        } else {

           Flash::addMessage('Failed to save data', Flash::INFO);
        }
		$this->redirect('/profile/show');
    }
	
    /**
     * Update the profile
     *
     * @return void
     */
    public function updateAction()
    {
        if ($this->user->updateProfile($_POST)) {

            Flash::addMessage('Changes saved');

            $this->redirect('/profile/show');

        } else {

            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);

        }
    }
}
