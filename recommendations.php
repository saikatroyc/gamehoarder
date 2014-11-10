<!-- <?php
    session_start(); 
    if (isset($_SESSION['username'])) {
    } else {
        header("Location: index.php");
    }   
?>-->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Game Hoarder</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
    <link href="css/sidebar.css" rel="stylesheet">
  </head>
<!-- NAVBAR
================================================== -->
  <body>
    <div class="navbar-wrapper" style="opacity: 0.6; top: 0px;">
      <div class="container">

        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Game Hoarder</a>
            </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="home.php">Home</a></li>
          <li><a href="graphs.php" id="graphs">TrackUrGames</a></li>
          <li class="active"><a href="recommendations.php" id="recommend">Recommendations</a></li>
          <li><a href="search.php">Search</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="change_password.php">Change Password</a></li>
              <li class="divider"></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
        </div><!-- /.navbar-collapse -->
        </div>
        </div>

        <div class="row">
        <div class="col-lg-4">
        <div id="sidebar-wrapper" style="top: 50px;">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Recommendation options
                    </a>
                </li>
                <li>
                    <a href="#">[Options]</a>
                </li>
            </ul>
        </div>
        </div><!-- end col-->
        

        </div><!--end row-->





      </div> <!-- end container-->
    </div>
<div class="container">
    <div class="page-header">
        <div class="clearfix">
            <div class="col-md-12 col-sm-6 col-xs-12 text-center">
                <h1>Recommended games<br></h1>
            </div>
        </div>
    </div>
</div> <!--end container-->
<?php
require 'pdo_db_connect.php';
$conn = func_connect_db("gamehoarder");
$username=$_SESSION['username'];
$game_list=func_getRecommendations($conn, $username, 10);
$numrows = count($game_list);
if ($numrows > 0) {
    echo "<div class=\"container\">";
    for ($i = 0;$i < $numrows;$i++) { 
        echo"<div id=\"gamerow". $i."\" class=\"row\">".
            "<div class=\"col-md-6\" >
                <h5>" . $game_list[$i]['game'] . "</h5>
            </div>
            <div class=\"col-md-6\" >
                <p><a href=\"#\" id=\"mybutton1\" class=\"btn btn-default\" onclick=\"deleteGameUser('". $game_list[$i]['game'] ."','".$_SESSION['username']. "','gamerow".$i."')\">Delete</a></p>
            </div></div>"; 
    }
    echo"</div>";
} else {
    echo "Failed to get recommendations";
}
?>    

      <!-- FOOTER -->
      <div class="footer navbar-fixed-bottom">
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p class="pull-right">&copy; 2014 GameHoarder; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
    $("#about").on('click',
    function() {
        about();
    });
    $("#graphs").on('click',
    function() {
        graphs();
    });
    $("#recommend").on('click',
    function() {
        recommend();
    });
    function about()
    {
        document.getElementById("info").innerHTML="game collection site";
    }
    function graphs()
    {
        document.getElementById("info").innerHTML="TBD://track your games";
    }
    function recommend()
    {
        document.getElementById("info").innerHTML="TBD://game recommendations";
    }
    </script>
    <script>
    </script>
<?php //echo "hello ". $_SESSION['username'];?>
  </body>
</html>

