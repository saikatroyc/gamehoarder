<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/respond.js"></script>
</head>
<body class="container">
<?php
if (isset($_POST['register'])) {
    // signup the new user in a different form
    header("Location: register.php");
}
session_start();
$username = $_POST['username'];
$password = $_POST['password'];
if ($username && $password) {
    require 'pdo_db_connect.php';
    $conn = func_connect_db("gamehoarder");
    echo "connected";
    if ($conn) {
        // expected assoc array
        $record = func_getUserCredential($conn, $username);
        if ($record != NULL) {
            //print_r($record);
            $dbuser = $record['name'];
            $passwd = $record['passwd'];
            if ($passwd == $password && $dbuser == $username) {
                $_SESSION['username'] = $dbuser;
                echo "You are in ". "!!<br>";
                // user is validated. time for action
                echo "<h4>session started at:". date('l'). date('H:i'). "hrs</h4>";
                echo "<p><a href='logout.php'>Logout</a></p>";
                $ret = true;
            } else {
                echo "invalid password";
                $ret = false;
            }
        } else {
            echo "Username doesnt exist!";
            $ret = false;
        }
        if ($ret == false) {
            echo "<p><a href='index.php'>Click</a> to try again</p>";
        }
    }
    // once done close db
    //func_closeDbConection($conn);
    $conn = NULL;
} else {
    echo "username password needed to login!";
    echo "<p><a href='index.php'>Click</a> to try again</p>";
} 
?>

</body>
</html>
