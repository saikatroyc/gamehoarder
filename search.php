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
    <script src="http://fb.me/react-with-addons-0.11.2.js"></script>
    <script src="http://fb.me/JSXTransformer-0.11.2.js"></script>
</head>
<body>
<div id="wrapper">
    
      <form name="search_form" class="navbar-form navbar-left" role="search" method="post">
        <div class="form-group">
          <input name="search_input" type="text" class="form-control" placeholder="Search Games">
          <input type="submit" name="search" class="btn btn-default" value="Search">
          <input type="submit" name="dev" class="btn btn-default" value="Search by Dev">
        </div>       
      </form>
    
      <a href="member.php">Click here</a> to go back.
        <div class="container" id="searchresults">
    
        </div>

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
