<?php session_start(); ?>
<!DOCTYPE html>

<?php
    if (isset($_SESSION['username'])) {
    } else {
        header("Location: index.php");
    }
?>
<html lang='en'>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Game Hoarder - Search</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
  </head>
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
          <li><a href="recommendations.php" id="recommend">Recommendations</a></li>
          <li class="active"><a href="#">Search</a></li>
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
                <h1>Search your favourite games!</br><small>You can search your games by name or by developer name.</small></h1>
            </div>
        </div>
    </div>
    <div class="clearfix">
      <form name="search_form" class="col-md-12" role="search" method="post">
        <div class="form-group">
          <input name="search_input" type="text" class="form-control" placeholder="Explore">
          <br>
          <input type="submit" name="search" class="btn btn-primary btn-lg btn-block" value="Search by Game">
          <br>
          <input type="submit" name="dev" class="btn btn-primary btn-lg btn-block" value="Search by Developer">
          <span><a href="#" class="pull-left">Need help?</a></span>
        </div>       
      </form>
    </div>
</div> <!--end container-->
<!-- javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/contenthover.js"></script>
</body>

<?php
if (isset($_POST['search']) || isset($_POST['dev'])) {
    $search = $_POST['search_input'];
    if ($search !== "") {
    require 'pdo_db_connect.php';
    $conn = func_connect_db("gamehoarder");
    if ($conn) {
        if (isset($_POST['search'])) {
            $game_list=func_getGames($conn, $search);
        } else {
            $game_list=func_getGamesbyDev($conn, $search);
        }
    }
    $numrows = count($game_list);
    if ($numrows > 0) {
        echo "<div class=\"container\">
        <div class=\"row\">";
        for ($i = 0;$i < $numrows;$i++) {
            $rating=func_getGameRating($conn, $game_list[$i]['name']);
            echo"
                <div class=\"container thumbnail col-md-3\" style=\"padding-left:10px;height:500px;\"><float>
                <img src=\"" . func_getGameImage($conn, $game_list[$i]['name']) ."\" width=\"300\" height=\"240\"/></float><float>
                    <div class=\"caption\">
                        <h5><strong>". $game_list[$i]['name'] . "</strong></h5>";
                        if($rating==NULL)
                        {
                            echo"<p>No rating!</p>";
                        }
                        else
                        {
                            echo"<p>Rating: " . $rating . "</p>";
                        }
                        
                        echo"<p>Year: " . $game_list[$i]['year'] . "</p>
                        <p>Genre: " . $game_list[$i]['genre'] ."</p>
                        <p>Platform: " . $game_list[$i]['platform'] ."</p>
                        <p id=\"mybutton1\" class=\"btn btn-primary\" onclick=\"insertGameUser('". str_replace("'","\'",$game_list[$i]['name']) ."','".$_SESSION['username']. "','".$game_list[$i]['platform']. "')\">Add</p>
                    </div></float>
                </div>";
            if($i>0 && $i%4==0)
                echo"<br><br><br><br><br>";
        }
        echo"</div></div>";
    }
    }
}
?>

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
/*                        if(sres.length>0)
                        {
                            if(sres!='')
                            {
                                location.reload();
                            }
                            else
                                alert('Communication NK ERROR');
                        }
                        else
                            alert('Communication N2 ERROR');*/
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
<script type="text/javascript">
    $('document').ready(function(){
    $('.myhover').contenthover({
        overlay_width:240,
        overlay_height:160,
        effect:'slide',
        slide_direction:'right',
        overlay_x_position:'right',
        overlay_y_position:'center',
        overlay_background:'#000',
        overlay_opacity:0.8
    });
    });
</script>
</html>
