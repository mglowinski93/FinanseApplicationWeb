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
			'expense_categories' => $this->user->getExpenseCategories(),
			'payment_types' => $this->user->getPaymentTypes()
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
     * Show add income category page
     *
     * @return void
     */
    public function incomeCategoryAddAction()
    {	
        View::renderTemplate('Profile/add_income_category.html');
    }
	
	/**
     * Add the income category
     *
     * @return void
     */
    public function incomeCategorySaveAction()
    {	
        if ($this->user->addIncomeCategory($_POST)) {

            Flash::addMessage('Income category added');

        } else {
			Flash::addMessage('Failed to add income category', Flash::INFO);
        }
		$this->redirect('/profile/show');
    }
	
	/**
     * Remove the income category
     *
     * @return void
     */
    public function incomeCategoryRemoveAction()
    {	
        if ($this->user->removeIncomeCategory($_POST)) {

            Flash::addMessage('Income category removed');

        } else {
			Flash::addMessage('Failed to remove income category', Flash::INFO);
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
     * Show add expense page
     *
     * @return void
     */
    public function expenseCategoryAddAction()
    {	
        View::renderTemplate('Profile/add_expense_category.html');
    }
	
	/**
     * Save the expense type
     *
     * @return void
     */
    public function expenseCategorySaveAction()
    {	
        if ($this->user->addExpenseCategory($_POST)) {

            Flash::addMessage('Expense category added');

        } else {
			Flash::addMessage('Failed to add expense category', Flash::INFO);
        }
		$this->redirect('/profile/show');
    }
	
	/**
     * Remove the expense category
     *
     * @return void
     */
    public function expenseCategoryRemoveAction()
    {	
        if ($this->user->removeExpenseCategory($_POST)) {

            Flash::addMessage('Expense category removed');

        } else {
			Flash::addMessage('Failed to remove expense category', Flash::INFO);
        }
		$this->redirect('/profile/show');
    }
	
	/**
     * Show the form for editing the payment category
     *
     * @return void
     */
    public function editPaymentTypeAction()
    {
        View::renderTemplate('Profile/edit_payment_type.html', [
            'payment' => $this->user->getPaymentTypeById($_POST['paymentTypeIdToEdit'])
        ]);
    }
	
	/**
     * Update the payment type
     *
     * @return void
     */
    public function paymentTypeUpdateAction()
    {
        if ($this->user->updatePaymentType($_POST)) {

            Flash::addMessage('Changes saved');

        } else {
			Flash::addMessage('Failed to save data', Flash::INFO);
        }
		$this->redirect('/profile/show');
    }
	
	/**
     * Show add payment page
     *
     * @return void
     */
    public function paymentTypeAddAction()
    {	
        View::renderTemplate('Profile/add_payment_type.html');
    }
	
	/**
     * Add the payment type
     *
     * @return void
     */
    public function paymentTypeSaveAction()
    {	
        if ($this->user->addPaymentType($_POST)) {

            Flash::addMessage('Payment type added');

        } else {
			Flash::addMessage('Failed to add payment type', Flash::INFO);
        }
		$this->redirect('/profile/show');
    }
	
	/**
     * Remove the payment type
     *
     * @return void
     */
    public function paymentTypeRemoveAction()
    {	
        if ($this->user->removePaymentType($_POST)) {

            Flash::addMessage('Payment type removed');

        } else {
			Flash::addMessage('Failed to remove payment type', Flash::INFO);
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
