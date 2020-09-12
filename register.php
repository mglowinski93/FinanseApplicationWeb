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

        <title>Finance App Register</title>
    </head>
    <body class="text-center">
        <form class="form-user-credentials">

          <img class="mb-4" src="img/logo.png" alt="" width="72" height="72">
          <h1 class="h3 mb-3 font-weight-normal">Please fill in form to register</h1>

          <label for="inputName" class="sr-only">Email address</label>
          <input type="text" id="inputName" class="form-control" placeholder="Name" required autofocus>

          <label for="inputEmail" class="sr-only">Email address</label>
          <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required>

          <label for="inputPassword" class="sr-only">Password</label>
          <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
          <label for="retypeInputPassword" class="sr-only">Password</label>
          <input type="password" id="retypeInputPassword" class="form-control" placeholder="Retype Password" required>

          <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
          <p class="mt-5 mb-3 text-muted">&copy;Finance App 2020</p>
        </form>

    </body>
</html>