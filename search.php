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
    <title>Search</title>
    <link rel="stylesheet" type="text/css" href="css/mystyle.css" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    
      <form name="search_form" class="navbar-form navbar-left" role="search" method="post">
        <div class="form-group">
          <input name="search_input" type="text" class="form-control" placeholder="Search Games">
          <input type="submit" name="search" class="btn btn-default" value="Search">
        </div>       
      </form>
    
      <a href="member.php">Click here</a> to go back.

</div>
<!-- javascript -->
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
<?php
if (isset($_POST['search'])) {
    $search = $_POST['search_input'];
    require 'pdo_db_connect.php';
    $conn = func_connect_db("gamehoarder");
    if ($conn) {
        $game_list=func_getGames($conn, $search);
        foreach($game_list as $gl) {
            echo $gl;
            echo "<br>";
        }
    }
}
?>

</html>
