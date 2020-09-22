<?php

session_start();

if (!isset($_SESSION['logged_id']))
{
	if(isset($_POST['username']) AND isset($_POST['password']))
	{
		$username = htmlentities(filter_input(INPUT_POST, 'username'));
		$password = filter_input(INPUT_POST, 'password');
		
		require_once 'database.php';
		$user_query = $db->prepare('SELECT id, password FROM users WHERE username = :username');
		$user_query->bindValue(':username', $username, PDO::PARAM_STR);
		$user_query->execute();
		
		$user = $user_query->fetch();
		if($user && password_verify($password, $user['password']))
		{
			$_SESSION['logged_id'] = $user['id'];
			unset($_SESSION['bad_attempt']);
			header('Location: index.php');
		}
		else
		{
			$_SESSION['bad_attempt'] = true;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Tab icon -->
        <link rel="icon" href="img/logo.png">

        <!-- Custom CSS -->
        <link href="css/user_form.css" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

        <title>Finance App Login</title>
    </head>
    <body class="text-center">
        <form class="form-user-credentials" method="post">
          <img class="mb-4" src="img/logo.png" alt="" width="72" height="72">
          <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
          <label for="inputUsername" class="sr-only">Username</label>
          <input type="text" id="inputUsername" name="username" class="form-control" placeholder="Username" required autofocus>
          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		  
		  <?php
			if (isset($_SESSION['bad_attempt']))
			{
				echo '<div class="error">Wrong username or password</div>';
				unset($_SESSION['bad_attempt']);
			}
		  ?>
		  <p class="mt-5 mb-3"><a href="register.php">Register</a>, if you don't have an account</p>
          <p class="mt-5 mb-3 text-muted">&copy;Finance App 2020</p>
        </form>
    </body>
</html>