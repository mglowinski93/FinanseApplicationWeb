<?php

session_start();

if (isset($_SESSION['logged_id']))
{
	require_once 'database.php';
	if(isset($_POST['expense_value']))
	{
		$user_data = [
			'user_id' => $_SESSION['logged_id'],
			'expense_category_assigned_to_user_id' => $_POST['expenseCategory'],
			'payment_method_assigned_to_user_id' => $_POST['paymentType'],
			'amount' => $_POST['expense_value'],
			'date_of_expense' => $_POST['expense_date'],
			'expense_comment' => $_POST['expense_comment']
		];
		$insert_query = "INSERT INTO expenses (id, user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment) VALUES (NULL, :user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)";
		$stmt= $db->prepare($insert_query);

		if($stmt->execute($user_data) == false)
		{
			echo '<span style="color:red;">Server error. Sorry for inconvenience!</span>';
			exit();
		}
	}
	$expense_category_query = $db->prepare('SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = :user_id');
	$expense_category_query->bindValue(':user_id', $_SESSION['logged_id'], PDO::PARAM_STR);
	$expense_category_query->execute();
    $expense_categories = $expense_category_query->fetchAll();
	
	$payment_category_query = $db->prepare('SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :user_id');
	$payment_category_query->bindValue(':user_id', $_SESSION['logged_id'], PDO::PARAM_STR);
	$payment_category_query->execute();
    $payment_categories = $payment_category_query->fetchAll();

}
else
{
	header('Location: login.php');
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Tab icon -->
    <link rel="icon" href="img/logo.png">

    <!-- Custom CSS -->
    <link href="css/main.css" rel="stylesheet">
    <link href="css/data_forms.css" rel="stylesheet">
    <link href="css/modal.css" rel="stylesheet">
    <link href="css/fontello.css" rel="stylesheet">

    <script type="text/javascript" src="js/main.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <title>Finance App Expense</title>
  </head>
  <body onload="setExpenseTodaysDateInCalendar(); setBalanceStartingDate(); setBalanceEndingDate();">

    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top">
        <a class="navbar-brand" href="index.php">
          <img src="img/logo.png" width="30" height="30" alt="">
        </a>
        <a class="navbar-brand" href="index.php">Finance App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php"><i class="icon-home"></i>  Home<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="add_income.php"><i class="icon-dollar"></i>Add income</a>
            </li>
           <li class="nav-item active">
              <a class="nav-link" href="add_expense.php"><i class="icon-basket"></i>  Add expense</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="http://example.com" id="balanceDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-chart-bar"></i>  View balance</a>
              <div class="dropdown-menu" aria-labelledby="balanceDropdown">
                <a class="dropdown-item" href="<?="balance.php?startDate=".date('Y-m-01')."&endDate=".date("Y-m-t")?>">Current month</a>
                <a class="dropdown-item" href="<?="balance.php?startDate=".date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1))."&endDate=".date('Y-m-d', mktime(0, 0, 0, date('m'), 0))?>">Last month</a>
                <a class="dropdown-item" href="<?="balance.php?startDate=".date('Y-01-01')."&endDate=".date('Y-12-31')?>">Current Year</a>
                <a class="dropdown-item" href="#userDefinedBalanceDatesModal" data-toggle="modal" data-target="#userDefinedBalanceDatesModal">User definer period</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><i class="icon-wrench"></i>  Settings</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php"><i class="icon-logout"></i>  Logout</a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Modal -->
      <div class="modal fade" id="userDefinedBalanceDatesModal" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="col-12 modal-title">Chose dates to display balance</h4>
            </div>
            <div class="modal-body">
                <form class="form-income" action="./balance.php" method="get">
                    <div class="form-group row">
                      <label for="balanceStartingDate" class="col-sm-3 col-form-label">Start date</label>
                      <div class="col-sm-8">
                        <input type="date" class="form-control" id="balanceStartingDate" name="startDate" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="balanceEndingDate" class="col-sm-3 col-form-label">End Date</label>
                      <div class="col-sm-8">
                        <input type="date" class="form-control" id="balanceEndingDate" name="endDate" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-6 text-left">
                        <button type="submit" class="btn btn-danger btn-sm btn-block" data-dismiss="modal">Cancel</button>
                      </div>
                      <div class="col-6 text-right">
                        <button type="submit" class="btn btn-success btn-sm btn-block">Show balance</button>
                      </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main>
      <div class="container">
        <div class="py-5 text-center">
          <h2>Add expense</h2>
          <p class="lead">Fill-in below form to add expense</p>
        </div>

        <div class="row">
          <div class="col justify-content-center">
            <form class="form-expense" method="post">
              <div class="form-group row">
                <label for="expenseValue" class="col-sm-2 col-form-label">Value</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="expenseValue" name="expense_value" placeholder="100" required>
                </div>
              </div>

              <div class="form-group row">
                <label for="expenseComment" class="col-sm-2 col-form-label">Comment</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="expenseComment" name="expense_comment" placeholder="" rows="3"></textarea>
                </div>
              </div>

              <div class="form-group row">
                <label for="paymentTypeSelect" class="col-sm-2 col-form-label">Payment type</label>
                <div class="col-sm-10">
                  <select class="form-control" name="paymentType" id="paymentTypeSelect">
					<?php
						foreach ($payment_categories as $payment_category)
						{
						  echo'
							   <option value="'.$payment_category["id"].'">'.$payment_category["name"].'</option>
						      ';
						}
					?>
                  </select>
                </div>
              </div>

              <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-sm-2 pt-0">Category</legend>
                  <div class="col-sm-10">
                    <?php
						foreach ($expense_categories as $expense_category)
						{
						  echo'
						  <div class="form-check">
							<input class="form-check-input" type="radio" name="expenseCategory" id="'.$expense_category["name"].'" value='.$expense_category["id"].'>
							<label class="form-check-label" for="'.$expense_category["name"].'">
							  '.$expense_category["name"].'
							</label>
						  </div>
						  ';
						}
					?>
                  </div>
                </div>
              </fieldset>
              <div class="form-group row">
                <label for="expenseDate" class="col-sm-2 col-form-label">Date</label>
                <div class="col-sm-5">
                  <input type="date" class="form-control" id="expenseDate" name="expense_date" required>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6 text-left">
                  <button type="reset" class="btn btn-danger btn-sm">Cancel</button>
                </div>
                <div class="col-6 text-right">
                  <button type="submit" class="btn btn-success btn-sm">Add expense</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>

    <footer class="container text-center">
      <p class="mt-5 mb-3 text-muted">&copy;Finance App 2020</p>
    </footer>

  </body>
</html>