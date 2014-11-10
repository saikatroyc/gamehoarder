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
          <li class="active"><a href="#">Home</a></li>
          <li><a href="graphs.php" id="graphs">TrackUrGames</a></li>
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
      </div>
    </div>

<div class="container">
    <div class="page-header">
        <div class="clearfix">
            <div class="col-md-12 col-sm-6 col-xs-12 text-center">
                <h1>This is your current game collection!<br></h1>
            </div>
        </div>
    </div>
</div> <!--end container-->
<?php
require 'pdo_db_connect.php';
$conn = func_connect_db("gamehoarder");
$username=$_SESSION['username'];
$game_list=func_getGamesUser($conn, $username);
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
    echo "No games in collection yet!";
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
    function deleteGameUser(game,user,id) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        var xmlHttp=null;
        var sres;
        try
        {
            xmlHttp=new XMLHttpRequest();
            var str = "deleteuser=" + encodeURIComponent(user) + 
            "&deletegame=" + encodeURIComponent(game);
            str = "pdo_db_connect.php?" + str;
            xmlHttp.onreadystatechange=function()
            {   
                if(xmlHttp.readyState==4)
                {
                    if(xmlHttp.status==200)
                    {
                        sres=xmlHttp.responseText;
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
<?php //echo "hello ". $_SESSION['username'];?>
  </body>
</html>

