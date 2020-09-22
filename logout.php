<?php

session_start();

?>

<?php

session_start();
unset($_SESSION['logged_id']);
header('Location: login.php');

?>