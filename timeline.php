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
  <link rel="stylesheet" type="text/css" media="all" href="css/bootstrap-glyphicons.css">
  <link rel="stylesheet" type="text/css" media="all" href="css/timeline.css">
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
                    <a href="#">Timeline</a>
                </li>
                <li>
                    <a href="generalstats.php">General Stats</a>
                </li>
                <li>
                    <a href="generalstats.php#game_state_3d">Games in Progress</a>
                </li>
            </ul>
        </div>
        </div><!-- end col-->
        
        <div class="col-lg-8">
        <div id="page-content-wrapper" style="padding-left: 0px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1><p>Timeline</p></h1>
<!-- commenet dynamically create the php page here -->
<!-- get user history-->
   <?php
    if (isset($_SESSION['username'])) {
        require 'pdo_db_connect.php';
        $conn = func_connect_db("gamehoarder");
        if ($conn) {
            $username = $_SESSION['username'];
            $user_hist_list = func_getUserHistory($conn, $username);
            $rows = count($user_hist_list);
            if ($rows > 0) {
                   //print_r($user_hist_list);
                $str = '<ul class="timeline">';
                $count = 1;
                $direction = '';
                foreach ($user_hist_list as $row) {
                    if ($count > 0) {
                        $direction = 'class="timeline-inverted"';
                    } else {
                        $direction = '';
                    }
                    switch($row['action']) {
                        case 3:
                            // user joined gamehoarder
                            $str .= '<li><div class="tldate">'.$row['date'].'</div></li>';
                            break;
                        case 0:
                            // user added a game to repo
                            $str .=
                            '<li '. $direction .'>
                            <div class="tl-circ"></div>
                            <div class="timeline-panel">
                            <div class="tl-heading">
                                <h4>Game added to collection!</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i>'.$row['date'].'</small></p>
                            </div>
                            <div class="tl-body">
                                <p>Added <strong>'. $row['game'] . '</strong> to collection</p>
                            </div>
                            </div>
                            </li>';
                            break;
                        case 1:
                            // user started playing the game
                            $str .=
                            '<li '. $direction .'>
                            <div class="tl-circ"></div>
                            <div class="timeline-panel">
                            <div class="tl-heading">
                                <h4>You started a new game!</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i>'.$row['date'].'</small></p>
                            </div>
                            <div class="tl-body">
                                <p>Started playing <strong>'. $row['game'] . '</strong></p>
                            </div>
                            </div>
                            </li>'; 
                            break;
                        case 2:
                            // user ended a game
                            $str .=
                            '<li '. $direction .'>
                            <div class="tl-circ"></div>
                            <div class="timeline-panel">
                            <div class="tl-heading">
                                <h4>Congrats on game completion!</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i>'.$row['date'].'</small></p>
                            </div>
                            <div class="tl-body">
                                <p>Conquered <strong>'. $row['game'] . '</strong>!!!</p>
                            </div>
                            </div>
                            </li>';
                            break;
                        case 4:
                            // user ended a game
                            $str .=
                            '<li '. $direction .'>
                            <div class="tl-circ"></div>
                            <div class="timeline-panel">
                            <div class="tl-heading">
                                <h4>Deleted a game!</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i>'.$row['date'].'</small></p>
                            </div>
                            <div class="tl-body">
                                <p>Deleted <strong>'. $row['game'] . '</strong> from collection!</p>
                            </div>
                            </div>
                            </li>';
                            break;
                        case 5:
                            // user rated a game
                            $str .=
                            '<li '. $direction .'>
                            <div class="tl-circ"></div>
                            <div class="timeline-panel">
                            <div class="tl-heading">
                                <h4>Rated a game!</h4>
                                <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i>'.$row['date'].'</small></p>
                            </div>
                            <div class="tl-body">
                                <p>Rated <strong>'. $row['game'] . '</strong>!</p>
                            </div>
                            </div>
                            </li>';
                            break;
                            
                    }
                    $count *= -1;
                }
                $str.= '</ul>';
                //once string created echo it here!
                echo $str;
            } else {
                echo "<h1>It's lonely in here...</h1>";
            } 
        }     
    }
   ?>
                    </div>
                </div>
            </div>
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

