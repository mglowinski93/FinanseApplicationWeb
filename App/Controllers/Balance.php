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
//class Balance extends \Core\Controller
class Balance extends Authenticated
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
    public function showBalanceFromCurrentMonthAction()
    {
		$startDate = date('Y-m-01');
		$endDate = date("Y-m-t");
        $title = "Current month balance";
		
		$this->renderBalanceView($startDate, $endDate, $title);
    }
	
	public function showBalanceFromLastMonthAction()
    {
		$startDate = date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1));
		$endDate=date('Y-m-d', mktime(0, 0, 0, date('m'), 0));
        $title = "Last month balance";
		
		$this->renderBalanceView($startDate, $endDate, $title);
    }
	
	public function showBalanceFromCurrentYearAction()
    {
		$startDate = date('Y-01-01');
		$endDate=date('Y-12-31');
		$title = "Current year balance";
        
		$this->renderBalanceView($startDate, $endDate, $title);
    }
	
	public function showBalanceFromUserDefinedPeriodAction()
    {
		$startDate = $_POST['startDate'];
		unset($_POST['startDate']);
		$endDate = $_POST['endDate'];
		unset($_POST['endDate']);
		$title = 'Balance from '.$startDate.' to '.$endDate;
		
        $this->renderBalanceView($startDate, $endDate, $title);
    }
	
	private function renderBalanceView($startDate, $endDate, $title)
	{	
		$incomes = $this->user->getIncomes($startDate, $endDate);
		$expenses = $this->user->getExpenses($startDate, $endDate);
		$balance = $this->user->getBalance($startDate, $endDate);
		
		 View::renderTemplate('Balance/index.html', [
			'incomes' => $incomes,
			'expenses' => $expenses,
			'balance' => $balance,
			'title' => $title
		]);
	}
}