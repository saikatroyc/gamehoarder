<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>GameHoarder</title>

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
              <a class="navbar-brand" href="#">GameHoarder</a>
            </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li class="active"><a href="index.php">About Us</a></li>
          <li class="active"><a href="privacy.php">Privacy</a></li>
          <li class="active"><a href="terms.php">Terms</a></li>
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
                    <h2>Terms and Conditions</h2>
                    <p>
                    </p>
                </div>
            </div>
        </div>
    </div>


     <!-- FOOTER -->
      <div class="footer navbar-fixed-bottom">
        <p class="pull-right">&copy; 2014 GameHoarder; <a href="privacy.php">Privacy</a> &middot; <a href="#">Terms</a> &middot; <a href="#">Back to top</a></p>
      </div>

    </div><!-- /.container -->


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
    </script>
  </body>
</html>

