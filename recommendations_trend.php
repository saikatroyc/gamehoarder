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
          <li><a href="generalstats.php" id="graphs">TrackUrGames</a></li>
          <li class="active"><a href="#" id="recommend">Recommendations</a></li>
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
                    <a href="recommendations.php">By Your Most Popular Genre</a>
                </li>
                <li>
                    <a href="#">Trending</a>
                </li>
            </ul>
        </div>
        </div><!-- end col-->
        
        <div class="col-lg-8">
        <div id="page-content-wrapper" style="padding-left: 0px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1><p>Recommendations</p></h1>
<!-- commenet dynamically create the php page here -->
<!-- get user history-->
                    <?php
                        require 'pdo_db_connect.php';
                        $conn = func_connect_db("gamehoarder");
                        $username=$_SESSION['username'];
                        $game_list = func_getTopGamesByUsers($conn, $username, 20);
                        $numrows = count($game_list);
                        if ($numrows > 0) {
                        echo "<div class=\"container\">
                        <div class=\"row\">";
                        for ($i = 0;$i < $numrows;$i++) {
                        echo"
<div class=\"container thumbnail col-lg-3\" >
    <img src=\"" . func_getGameImage($conn, $game_list[$i]['name']) ."\" width=\"300\" height=\"240\"/>
    <div class=\"caption\">
    <h5><strong>". $game_list[$i]['name'] . "</strong></h5> 
    <h5><strong>users : ". $game_list[$i]['count'] . "</strong></h5> 
    <p id=\"mybutton1\" class=\"btn btn-primary\" onclick=\"insertGameUser('". str_replace("'","\'",$game_list[$i]['name']) ."','".$_SESSION['username']. "','".$game_list[$i]['platform']. "')\">Add</p>
    </div>
</div>";
        }   
        echo"</div></div>";
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


      <!-- FOOTER -->
      <div class="footer navbar-fixed-bottom">
        <p class="pull-right">&copy; 2014 GameHoarder; <a href="#">Privacy</a> &middot; <a href="#">Terms</a> &middot; <a href="#">Back to top</a></p>
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
    <script type="text/javascript">
    function insertGameUser(game,user,platform) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        var xmlHttp=null;
        var sres;
        try
        {
            xmlHttp=new XMLHttpRequest();
            var str = "insertuser=" + encodeURIComponent(user) + 
            "&insertgame=" + encodeURIComponent(game) +
            "&insertplatform=" + encodeURIComponent(platform);
            str = "pdo_db_connect.php?" + str;
            alert(game+" added to collection");
            xmlHttp.onreadystatechange=function()
            {   
                if(xmlHttp.readyState==4)
                {
                    if(xmlHttp.status==200)
                    {
                        sres=xmlHttp.responseText;
                        alert(sres);
                        if(sres.length>0)
                        {
                            if(sres!='')
                            {
                                location.reload();
                            }
                            else
                                alert('Communication NK ERROR');
                        }
                        else
                            alert('Communication N2 ERROR');
                    }
                    else
                        alert('Communication ERROR. Returned status code:['+xmlHttp.status+']('+xmlHttp.statusText+')');
                }
            }
            xmlHttp.open('GET',str,true);
            xmlHttp.send();
        }    
        catch(e)
        {
            alert('Communication N1 ERROR:['+e.message+']');
        } 
    }
    </script>
  </body>
</html>

