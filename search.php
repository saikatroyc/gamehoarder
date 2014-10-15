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
    <link rel="stylesheet" type="text/css" href="css/contenthover.css" />
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
    <script src="http://fb.me/react-with-addons-0.11.2.js"></script>
    <script src="http://fb.me/JSXTransformer-0.11.2.js"></script>
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
              <a class="navbar-brand" href="#">GameHoarder</a>
            </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="member.php">Home</a></li>
          <li><a href="#Graphs" id="graphs">TrackUrGames</a></li>
          <li><a href="#Lucky" id="recommend">Recommendations</a></li>
          <li class="active"><a href="#">Search</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">MyAccount <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="change_password.php">ChangePassword</a></li>
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
                <h1>Search your favourite games</br><small>Now you can search your games by name or by developers</small></h1>
            </div>
        </div>
    </div>
    <div class="clearfix">
      <form name="search_form" class="col-md-12" role="search" method="post">
        <div class="form-group">
          <input name="search_input" type="text" class="form-control" placeholder="Explore">
          <input type="submit" name="search" class="btn btn-primary btn-lg btn-block" value="Searchby Games">
          <input type="submit" name="dev" class="btn btn-primary btn-lg btn-block" value="Searchby Dev">
          <span><a href="#" class="pull-left">Need help?</a></span>
        </div>       
      </form>
    </div>
</div> <!--end container-->
<div id="wrapper">
        <div class="container" id="searchresults">
<img id="d2" src="images/bg.png" width="300" height="240" />
<div class="contenthover">
    <h3>Caption</h3>
    <p>Game details,rating </p>
    <p><a href="#" class="mybutton">Add</a></p>
</div>
        </div>

</div>
<!-- javascript -->
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/contenthover.js"></script>
</body>
<?php
if (isset($_POST['search'])) {
    $search = $_POST['search_input'];
    require 'pdo_db_connect.php';
    $conn = func_connect_db("gamehoarder");
    if ($conn) {
        $game_list=func_getGames($conn, $search);
        echo '<script>results = ' . json_encode($game_list) . ';</script>';
    }
}

if (isset($_POST['dev'])) {
    $search = $_POST['search_input'];
    require 'pdo_db_connect.php';
    $conn = func_connect_db("gamehoarder");
    if ($conn) {
        $game_list=func_getGamesbyDev($conn, $search);
        echo '<script>results = ' . json_encode($game_list) . ';</script>';
    }
}
?>
<script type="text/javascript">
    $('document').ready(function(){
    $('#d2').contenthover({
    effect:'slide',
    slide_speed:300,
    overlay_background:'#000',
    overlay_opacity:0.5
    });
    });
</script>
<script type="text/jsx">
        /**
         * @jsx React.DOM
         */
        (function(){

            var res = results;

            function render() {
                React.renderComponent(
                <Search />,
                document.getElementById('searchresults')
                );
            }

            var Search = React.createClass({
                getInitialState: function() {
                    return {
                        items: res
                    }
                },

                _renderItem: function(item) {
                    return (
                       <ResultItem game={item} />
                    );
                },

                render: function() {
                    return (
                        <ul class="list-group">
                            {this.state.items.map(this._renderItem)}
                        </ul>
                    );
                }
            });

            var ResultItem = React.createClass({
                render: function() {
                    return (
                        <div>
                            <li class="list-group-item">
                                {this.props.game}
                            </li>
                            <input type="submit" name="add" class="btn btn-default" value="add to collection" />
                        </div>
                    );
                }
            })

            render();
        })();
</script>

</html>
