<!DOCTYPE html>

<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <title>
        HTML Document Structure
    </title>
    <link rel="stylesheet" type="text/css" href="style.css" />

</head>
<body>
<div id="wrapper">

	<form name="login-form" class="login-form" action="login.php" method="post">
	
		<div class="header">
		<h1>Login Form</h1>
		<span>If you existing user, login back!</span>
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

</body>
<?php
    if (isset($_POST['register'])) {
        // signup the new user in a different form
        header("Location: http://localhost/saikat/db_v1/register.php");
    }
?>

</html>
