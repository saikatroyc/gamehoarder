<?php session_start(); ?>
<!DOCTYPE html>

<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamehoarder</title>
    <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">

	<form name="login-form" class="login-form" action="" method="post">
	
		<div class="header">
		<h1>Gamehoarder Login</h1>
		<!--span>If you are existing user, login back!</span-->
		</div>
	
		<div class="content">
		<input name="username" type="text" class="input username" placeholder="Username" />
		<div class="user-icon"></div>
		<input name="password" type="password" class="input password" placeholder="Password" />
		<div class="pass-icon"></div>		
		</div>

		<div class="footer">
		<input type="submit" name="login" value="Login" class="button" />
		<input type="submit" name="register" value="Register" class="register" onclick=redirectRegister() />
		</div>
	
	</form>

</div>
<!-- javascript -->
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
<?php
    if (isset($_POST['register'])) {
        // signup the new user in a different form
        header("Location: register.php");
    } elseif (isset($_POST['login'])) {
        // insert code to check valid user
        //session_start();
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username && $password) {
            require 'pdo_db_connect.php';
            $conn = func_connect_db("gamehoarder");
            if ($conn) {
                // expected assoc array
                $record = func_getUserCredential($conn, $username);
                if ($record != NULL) {
                    //print_r($record);
                    $dbuser = $record['name'];
                    $passwd = $record['passwd'];
                    if ($passwd == $password && $dbuser == $username) {
                        $_SESSION['username'] = $dbuser;
                        echo "You successfully logged in!<br>";
                        // user is validated. time for action
                        echo "<h4>session started at: ". date('l'). date('H:i'). "hrs</h4>.<br>";
                        echo "<p><a href='logout.php'>Logout</a></p>";
                        $ret = true;
                    } else {
                        echo "Invalid password.";
                        $ret=false;
                    }
                } else {
                    echo "Username doesn't exist!";
                    $ret = false;
                }
                if ($ret == false) {
                    echo "<p><a href='index.php'>Click</a> to try again</p>";
                } else {
                    // session user is set, retrieve this is member.php
                    //header("Location: home.php");
                }
            }
            // once done close db
            //func_closeDbConection($conn);
            $conn = NULL;
        } else {
            echo "Username and password are needed to login!";
            echo "<p><a href='index.php'>Click</a> to try again</p>";
        } 
    }
?>

</html>