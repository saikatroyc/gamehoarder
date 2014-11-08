<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format";
            goto end; 
        }
        $passwd = $_POST['password'];
        require_once 'pdo_db_connect.php';
        $conn = func_connect_db("gamehoarder");
        if (func_is_user_exists($conn, $username) == true) {
            // user exists. prompt for a new name
            echo "username already exists"; 
            goto end;
        } else {
            //echo "user doesnt exist"; 
            // username name is new. add it to db
            //func_add_user($dth, $username, $password, $email);
            $user_record['name'] = $username;
            $user_record['pass'] = $passwd;
            $user_record['email'] = $email;
            func_insert_new_user($conn, $user_record);
            func_closeDbConection($conn);
            // toast user that account created. prompt to login
            echo "<h2>registered!! please <a href='index.php'>login</a> back again to continue</h2>"; 
        }
    }
end:
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
</head>
<body>

<div id="wrapper">
	<form name="Registration-form" class="login-form" action="" method="post">
	
		<div class="header">
		<h1>Registration Form</h1>
		<span>Select a Username, Password, email</span>
		</div>
	
		<div class="content">
		<input name="username" type="text" class="input username" placeholder="Username" required/>
		<input name="password" type="password" class="input password" placeholder="password" required/>
		<input name="email" type="text" class="input email" placeholder="email@domain" required/>
		</div>

		<div class="footer">
		<input type="submit" name="submit" value="Register" class="register" />
		</div>
	</form>

</div>
<div class="gradient"></div>
</body>
</html>
