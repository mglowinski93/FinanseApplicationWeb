<?php

	session_start();
	
	if(isset($_POST['email']))
	{
		$valid_data=true;
		
		$username = filter_input(INPUT_POST, 'username');

		if(strlen($username)<3 || strlen($username>20))
		{
			$valid_data=false;
			$_SESSION['e_username'] = "Username must contain between 3 and 20 characters";
		}
		
		if(ctype_alnum($username)==false)
		{
			$valid_data=false;
			$_SESSION['e_username']="Username can only contain characters";
		}
		
		$email = filter_input(INPUT_POST, 'email');
		$validated_email = filter_var($email, FILTER_SANITIZE_EMAIL);
		if(filter_var($validated_email, FILTER_VALIDATE_EMAIL)==false || $email!=$validated_email)
		{
			$valid_data=false;
			$_SESSION['e_email']="Email incorrect";
		}
		
		$password_first_try = filter_input(INPUT_POST, 'password_first_try');
		$password_second_try = filter_input(INPUT_POST, 'password_second_try');
		
		if((strlen($password_first_try)<4) || (strlen($password_first_try)>20))
		{
			$valid_data=false;
			$_SESSION['e_password']="Password must contain between 4 and 20 characters";
		}
		
		if($password_first_try!=$password_first_try)
		{
			$valid_data=false;
			$_SESSION['e_password']="Passwords are not identical";
		}
		
		$hashed_password = password_hash($password_first_try, PASSWORD_DEFAULT);
		
		$secret_key = "6LccYMsZAAAAAAgMfBxwg8TiQeemqasY1n3b4QAe";
		$captcha_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
		
		$response = json_decode($captcha_response);
		
		if($response->success==false)
		{
			$valid_data=false;
			$_SESSION['e_bot']="Confirm, that you are human";
		}
		
		require_once 'database.php';
		
		$username_query = $db->prepare('SELECT username FROM users WHERE username = :username');
		$username_query->bindValue(':username', $username, PDO::PARAM_STR);
		$username_query->execute();
		
		$user = $username_query->fetch();
		
		if($user)
		{
			$valid_data=false;
			$_SESSION['e_username']="This username is already taken. Please choose another one";
		}
		
		$email_query = $db->prepare('SELECT email FROM users WHERE email = :email');
		$email_query->bindValue(':email', $email, PDO::PARAM_STR);
		$email_query->execute();
		
		$user = $email_query->fetch();
		
		if($user)
		{
			$valid_data=false;
			$_SESSION['e_email']="There is already account assigned to this email address";
		}
			
		if($valid_data==true)
		{	
			$user_data = [
				'username' => $username,
				'password' => $hashed_password,
				'email' => $email
			];
			$insert_query = "INSERT INTO users (id, username, password, email) VALUES (NULL, :username, :password, :email)";
			$stmt= $db->prepare($insert_query);

			if($stmt->execute($user_data) == false)
			{
				echo '<span style="color:red;">Server error. Sorry for inconvenience!</span>';
				exit();
			}
			
			$user_query = $db->prepare('SELECT id FROM users WHERE username = :username');
			$user_query->bindValue(':username', $username, PDO::PARAM_STR);
			$user_query->execute();
			$user = $user_query->fetch();
			
			$copy_expenses_category_query = $db->prepare("INSERT INTO incomes_category_assigned_to_users (id, user_id, name) SELECT NULL, :user_id, name FROM incomes_category_default");
			$copy_expenses_category_query->bindValue(':user_id', $user['id'], PDO::PARAM_STR);
			$copy_expenses_category_query->execute();
			
			$copy_incomes_category_query = $db->prepare("INSERT INTO expenses_category_assigned_to_users (id, user_id, name) SELECT NULL, :user_id, name FROM expenses_category_default");
			$copy_incomes_category_query->bindValue(':user_id', $user['id'], PDO::PARAM_STR);
			$copy_incomes_category_query->execute();
			
			$copy_payment_methods_query = $db->prepare("INSERT INTO payment_methods_assigned_to_users (id, user_id, name)  SELECT NULL, :user_id, name FROM payment_methods_default");
			$copy_payment_methods_query->bindValue(':user_id', $user['id'], PDO::PARAM_STR);
			$copy_payment_methods_query->execute();
			
			header('Location: login.php');
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
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <title>Finance App Register</title>
    </head>
    <body class="text-center">
        <form class="form-user-credentials" method="post">

          <img class="mb-4" src="img/logo.png" alt="" width="72" height="72">
          <h1 class="h3 mb-3 font-weight-normal">Please fill in form to register</h1>

          <label for="inputName" class="sr-only">Username</label>
          <input type="text" id="inputName" name="username" class="form-control" placeholder="Username" required autofocus>
		  <?php
			if (isset($_SESSION['e_username']))
			{
				echo '<div class="error">'.$_SESSION['e_username'].'</div>';
				unset($_SESSION['e_username']);
			}
		  ?>
		  

          <label for="inputEmail" class="sr-only">Email address</label>
          <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required>
		  <?php
		  if (isset($_SESSION['e_email']))
		  {
			echo '<div class="error">'.$_SESSION['e_email'].'</div>';
			unset($_SESSION['e_email']);
		  }
		  ?>

          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" id="inputPassword" name="password_first_try" class="form-control" placeholder="Password" required>
          <label for="retypeInputPassword" class="sr-only">Password</label>
          <input type="password" id="retypeInputPassword" name="password_second_try" class="form-control" placeholder="Retype Password" required>
		  <?php
		  if (isset($_SESSION['e_password']))
		  {
			echo '<div class="error">'.$_SESSION['e_password'].'</div>';
			unset($_SESSION['e_password']);
		  }
		  ?>
		  
		  <div class="g-recaptcha" data-sitekey="6LccYMsZAAAAAFFLTJbDW-bGqn27m4Yj-4KqEG0A"></div>
		  <?php
		  if (isset($_SESSION['e_bot']))
		  {
			echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
			unset($_SESSION['e_bot']);
		  }
		  ?>
		  
          <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
          <p class="mt-5 mb-3 text-muted">&copy;Finance App 2020</p>
        </form>

    </body>
</html>