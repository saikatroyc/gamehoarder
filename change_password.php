<!DOCTYPE html>

<?php
    session_start(); 
    if (isset($_SESSION['username'])) {
    } else {
        header("Location: index.php");
    }
?>
<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    
    <form name="change-pass-form" role="form" action="" method="post">
      <div class="form-group">
        <label for="curpass">Current password</label>
        <input name="curr_password" type="password" class="form-control" id="curpass" placeholder="Enter current password">
      </div>
      <div class="form-group">
        <label for="newpass">New Password</label>
        <input name="new_password" type="password" class="form-control" id="newpass" placeholder="Enter new password">
      </div>
      <div class="form-group">
      <div class="form-group">
        <label for="newpass2">Re-Enter New Password</label>
        <input name="new_password2" type="password" class="form-control" id="newpass2" placeholder="Re-enter new password">
      </div>
      </div>
      <input type="submit" name="change_password" value="Change Password" class="btn btn-default" />
    </form>
    
    <a href="member.php">Click here</a> to go back.

</div>
<!-- javascript -->
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
<?php
    if (isset($_POST['change_password'])) {
        // insert code to check valid user
        $username=$_SESSION['username'];
        $curr_password = $_POST['curr_password'];
        if ($curr_password) {
            require 'pdo_db_connect.php';
            $conn = func_connect_db("gamehoarder");
            if ($conn) {
                // expected assoc array
                $record = func_getUserCredential($conn, $username);
                if ($record != NULL) {
                    //print_r($record);
                    $passwd = $record['passwd'];
                    if ($passwd == $curr_password) {
                        $new_password = $_POST['new_password'];
                        $new_password2 = $_POST['new_password2'];
                        if($new_password==$new_password2)
                        {
                            func_changePassword($conn, $username, $new_password);
                            $ret = true;
                        }
                        else
                        {
                            echo "Passwords don't match";
                            $ret=false;
                        }
                    } else {
                        echo "Invalid password.";
                        $ret=false;
                    }
                } else {
                    echo "Username doesn't exist!";
                    $ret = false;
                }
                if ($ret == false) {
                    echo "<p><a href='change_password.php'>Click</a> to try again</p>";
                } else {
                    // session user is set, retrieve this is member.php
                    echo "Password successfully changed!";
                    echo "<p><a href='member.php'>Click</a> to get back to. </p>";
                }
            }
            // once done close db
            //func_closeDbConection($conn);
            $conn = NULL;
        } else {
            echo "Form incomplete.";
            echo "<p><a href='change_password.php'>Click</a> to try again</p>";
        } 
    }
?>

</html>
