<?php

session_start();

if (isset($_SESSION['logged_id']))
{
	require_once 'database.php';
	if(isset($_GET['startDate']) && isset($_GET['endDate']))
	{	
		$start_date = $_GET['startDate'];
		$end_date = $_GET['endDate'];
		$user_id = $_SESSION['logged_id'];
		
		$expenses_query = "SELECT expenses_category_assigned_to_users.name, SUM(expenses.amount) FROM expenses INNER JOIN expenses_category_assigned_to_users ON expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id WHERE expenses.date_of_expense BETWEEN :start_date AND :end_date AND expenses.user_id = :user_id GROUP BY expenses.expense_category_assigned_to_user_id";
		$stmt_expenses= $db->prepare($expenses_query);
		$stmt_expenses->bindParam(':start_date', $start_date);
		$stmt_expenses->bindParam(':end_date', $end_date);
		$stmt_expenses->bindParam(':user_id',$user_id);
		
		if($stmt_expenses->execute() == false)
		{
			echo '<span style="color:red;">Server error. Sorry for inconvenience!</span>';
			exit();
		}
		else
		{
			$expenses = $stmt_expenses->fetchAll();
		}
		
		$incomes_query = "SELECT incomes_category_assigned_to_users.name, SUM(incomes.amount) FROM incomes INNER JOIN incomes_category_assigned_to_users ON incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id WHERE incomes.date_of_income BETWEEN :start_date AND :end_date AND incomes.user_id = :user_id GROUP BY incomes.income_category_assigned_to_user_id";
		$stmt_incomes= $db->prepare($incomes_query);
		$stmt_incomes->bindParam(':start_date', $start_date);
		$stmt_incomes->bindParam(':end_date', $end_date);
		$stmt_incomes->bindParam(':user_id', $user_id);

		if($stmt_incomes->execute() == false)
		{
			echo '<span style="color:red;">Server error. Sorry for inconvenience!</span>';
			exit();
		}
		else
		{
			$incomes = $stmt_incomes->fetchAll();
		}
		
		
		$summary_query = "SELECT (SELECT SUM(amount) FROM incomes WHERE user_id = :income_user_id and date_of_income BETWEEN :income_start_date and :income_end_date) - (SELECT SUM(amount) FROM expenses WHERE user_id = :expense_user_id and date_of_expense BETWEEN :expense_start_date and :expense_end_date)";
		$stmt_summary= $db->prepare($summary_query);
		$stmt_summary->bindParam(':income_start_date', $start_date);
		$stmt_summary->bindParam(':income_end_date', $end_date);
		$stmt_summary->bindParam(':income_user_id', $user_id);
		$stmt_summary->bindParam(':expense_start_date', $start_date);
		$stmt_summary->bindParam(':expense_end_date', $end_date);
		$stmt_summary->bindParam(':expense_user_id', $user_id);

		if($stmt_summary->execute() == false)
		{
			echo '<span style="color:red;">Server error. Sorry for inconvenience!</span>';
			exit();
		}
		else
		{
			$summary = $stmt_summary->fetch();
			$balance = $summary["(SELECT SUM(amount) FROM incomes WHERE user_id = ? and date_of_income BETWEEN ? and ?) - (SELECT SUM(amount) FROM expenses WHERE user_id = ? and date_of_expense BETWEEN ? and ?)"];
		}
		
	}
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
    <link href="css/balance.css" rel="stylesheet">
    <link href="css/fontello.css" rel="stylesheet">

    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="js/piechart.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <title>Finance App Income</title>
  </head>
  <body onload="setBalanceStartingDate(); setBalanceEndingDate();">

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
             <li class="nav-item">
                <a class="nav-link" href="add_expense.php"><i class="icon-basket"></i>  Add expense</a>
              </li>
              <li class="nav-item active dropdown">
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
            <h2>Balance</h2>
            <p class="lead">Current month</p>
          </div>

          <div class="row">
              <div class="col-sm-6" id="incomeSummary">
                  <p class="lead">Incomes</p>
                    <table class="table table-striped">
                      <tbody>
						<?php
							foreach ($incomes as $income)
							{
							  echo'
								<tr>
								  <td>'.$income['name'].'</td>
								  <td>'.$income['SUM(incomes.amount)'].'</td>
								</tr>
								  ';
							}
						?>
                      </tbody>
                    </table>
              </div>
              <div class="col-sm-6" id="expenseSummary">
                  <p class="lead">Expenses</p>
                  <table class="table table-striped">
                      <tbody>
					  	<?php
							foreach ($expenses as $expense)
							{
							  echo'
								<tr>
								  <td>'.$expense['name'].'</td>
								  <td>'.$expense['SUM(expenses.amount)'].'</td>
								</tr>
								  ';
							}
						?>
                      </tbody>
                   </table>
              </div>
          </div>
          <div class="row" id="balanceSummary">
              <div class="col">
                  <p class="lead">Summary</p>
                  <p style="">Balance: <?= $balance ?> PLN</p>
				  <?php
					if($balance>=0)
					{
						echo '<p style="color: green">Great! You manage your finances well</p>';
					}
					else
					{
						echo '<p style="color: red">Too bad! You must think about reducing expenses</p>';
					}
				  ?>
              </div>
          </div>
          <div class="row charts" id="piechartSummary">
            <div class="col-sm-12" id="piechart"></div>
          </div>
        </div>
    </main>

    <footer class="container text-center">
      <p class="mt-5 mb-3 text-muted">&copy;Finance App 2020</p>
    </footer>

  </body>
</html>