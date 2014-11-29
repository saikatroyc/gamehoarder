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
  <script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
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
          <li class="active"><a href="#" id="graphs">TrackUrGames</a></li>
          <li><a href="recommendations.php" id="recommend">Recommendations</a></li>
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
                    <a href="graphs.php">
                        Tracker options
                    </a>
                </li>
                <li class="active">
                    <a href="timeline.php">Timeline</a>
                </li>
                <li>
                    <a href="#">General Stats</a>
                </li>
                <li>
                    <a href="#">Games in Progress</a>
                </li>
                <li>
                    <a href="#">Games Complete</a>
                </li>
            </ul>
        </div>
        </div><!-- end col-->
        
        <div class="col-lg-8">
        <div id="page-content-wrapper" style="padding-left: 0px;">
            <h1><p>General Stats</p></h1>
<!-- commenet dynamically create the php page here -->
<!-- get user history-->
   <?php
    if (isset($_SESSION['username'])) {
        require 'pdo_db_connect.php';
        $conn = func_connect_db("gamehoarder");
        if ($conn) {
            $username = $_SESSION['username'];
            $user_game_count = func_getUserGameCount($conn, $username);
            $user_platform_count = func_getUserPlatformCount($conn, $username);
            $user_genre_count = func_getUserGenreCount($conn, $username);
            $user_platform_most_games = func_getUserPlatformMax($conn, $username);
            $user_developer_most_games = func_getUserDeveloperMax($conn, $username);
            $user_first_game = func_getUserFirstGame($conn, $username);
            $user_game_longest = func_getUserLongestGame($conn, $username);
            
            echo "Total number of games: ".$user_game_count[0][0];
            echo "<br><br>";
            echo "Total number of platforms: ".$user_platform_count[0][0];
            echo "<br><br>";
            echo "Total number of genres: ".$user_genre_count[0][0];
            echo "<br><br>";
            echo "Platform with greatest number of games: ".$user_platform_most_games[0][0].", ".$user_platform_most_games[0][1];
            echo "<br><br>";
            echo "Developer with greatest number of games: ".$user_developer_most_games[0][0].", ".$user_developer_most_games[0][1];
            echo "<br><br>";
            echo "First game in collection: ".$user_first_game[0][0];
            echo "<br><br>";
            if($user_game_longest[0][1]==0)
                echo "No games completed!";
            else
                echo "Longest game to complete: ".$user_game_longest[0][0].", Number of days: ".abs($user_game_longest[0][1]);
        }     
    }
   ?>
        </div>
        </div><!--end col-->
        </div><!--end row-->





      </div> <!-- end container-->
    </div>

<?php
?>    

      <!-- FOOTER -->
      <div class="footer navbar-fixed-bottom">
        <p class="pull-right">&copy; 2014 GameHoarder; <a href="privacy.php">Privacy</a> &middot; <a href="terms.php">Terms</a> &middot; <a href="#">Back to top</a></p>
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

