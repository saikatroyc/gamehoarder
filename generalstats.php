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
  <script type="text/javascript"  src="https://www.google.com/jsapi"></script>
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
                    <a href="#game_state_3d">Games Progress Status</a>
                </li>
            </ul>
        </div>
        </div><!-- end col-->
        
        <div class="col-lg-8">

<div class="container-fluid">
  <!--<div class="row">
        <div id="chart_div">
        </div>
        <div id="filter_div"></div>
  </div>-->
  <div class="row">
    <div id="genre_3d" style="width: 700px; height: 500px;"></div>
  </div>
  <div class="row" style="padding-top: 10px;">
    <div id="game_state_3d" style="width: 700px; height: 500px;"></div>
  </div>
</div>
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
            $user_game_completion_stat = func_getGameCompletionStat($conn, $username);
            $user_game_count_by_genre = func_getUserCountByGenre($conn, $username);

            // chart for games in repo by genre
            $chart_array[0]=array('Genre', 'GameCount');
            for($i=1;$i<=count($user_game_count_by_genre);$i++)
            {
                $chart_array[$i]=array((string)$user_game_count_by_genre[$i]['genre'],intval($user_game_count_by_genre[$i]['genrecount']));
            }
            $data_chart_array=json_encode($chart_array);
            // chart for game completion stat
            $chart_array_game_stat[0]=array('GameStatus', 'GameCount');
            $chart_array_game_stat[1]=array((string)'inprogress',intval($user_game_completion_stat['inprogress'][0]));
            $chart_array_game_stat[2]=array((string)'complete',intval($user_game_completion_stat['complete'][0]));
            $chart_array_game_stat[3]=array((string)'notstarted',intval($user_game_completion_stat['notstarted'][0]));
            //print_r($user_game_completion_stat);
            $data_chart_array_game_stat=json_encode($chart_array_game_stat);
            //print_r($data_chart_array_game_stat);
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
 <script type="text/javascript">

      // Load the Visualization API and the controls package.
      google.load('visualization', '1.0', {'packages':['controls']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawDashboard);

      // Callback that creates and populates a data table,
      // instantiates a dashboard, a range slider and a pie chart,
      // passes in the data and draws it.
      function drawDashboard() {
        var chdata=JSON.parse( '<?php echo json_encode($chart_array) ?>' );
        var data = new google.visualization.DataTable();
        var data = google.visualization.arrayToDataTable(chdata);

        // Create a dashboard.
        var dashboard = new google.visualization.Dashboard(
            document.getElementById('chart_div'));

        // Create a range slider, passing some options
        var donutRangeSlider = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div',
          'options': {
            'filterColumnLabel': 'GameCount'
          }
        });

        // Create a pie chart, passing some options
        var pieChart = new google.visualization.ChartWrapper({
          'chartType': 'PieChart',
          'containerId': 'chart_div',
          'options': {
            'width': 400,
            'height': 400,
            'pieSliceText': 'value',
            'legend': 'right'
          }
        });

        // Establish dependencies, declaring that 'filter' drives 'pieChart',
        // so that the pie chart will only display entries that are let through
        // given the chosen slider range.
        dashboard.bind(donutRangeSlider, pieChart);

        // Draw the dashboard.
        dashboard.draw(data);
      }

</script>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var chdata=JSON.parse( '<?php echo json_encode($chart_array) ?>' );
        var data = new google.visualization.DataTable();
        var data = google.visualization.arrayToDataTable(chdata);
        var options = {
          title: ' Games by Genre in Repo',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('genre_3d'));
        chart.draw(data, options);
      }
</script> 
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var chdata=JSON.parse( '<?php echo json_encode($chart_array_game_stat) ?>' );
        var data = new google.visualization.DataTable();
        var data = google.visualization.arrayToDataTable(chdata);
        var options = {
          title: ' Games Completion Status',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('game_state_3d'));
        chart.draw(data, options);
      }
</script> 
<?php //echo "hello ". $_SESSION['username'];?>

  </body>
</html>

