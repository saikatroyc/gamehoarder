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
          <li class="active"><a href="#">AboutUs</a></li>
        </ul>
        </div><!-- /.navbar-collapse -->
        </div>
        </div>
      </div>
    </div>

    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="item active">
          <img src="images/back.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Welcome to Gamehoarder!!</h1>
              <p>Your onestop store for game collections</p>
              <p><a class="btn btn-lg btn-primary" href="login.php" role="button">GetStarted</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="images/back.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Track your games</h1>
              <p>Keep track of all your games collection. Get interactive visualization of your games</p>
              <p><a class="btn btn-lg btn-primary" href="#feat1" role="button">Learn more</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="images/back.jpg" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Recommendations</h1>
              <p>Get intuitive recommendations of hot trending games</p>
              <p><a class="btn btn-lg btn-primary" href="#feat2" role="button">Learn More</a></p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div><!-- /.carousel -->

    <div class="container marketing">



      <!-- START THE FEATURETTES -->

      <hr class="featurette-divider">
      <div class="row featurette">
        <div class="col-md-10">
          <h2 class="featurette-heading">GameHoarder <span class="text-muted">Manage your collection</span></h2>
          <p class="lead">GameHoarder is a video game management website that allows you to organize and visualize your
video game collection, as well as find recommendations for new games.</p>
        </div>
      </div>

      <hr class="featurette-divider">

      <div class="row featurette" id="feat1">
        <div class="col-md-10">
          <h2 class="featurette-heading">Visualize <span class="text-muted">View your collection</span></h2>
          <p class="lead">View graphs to learn more about your collection, find your most owned genres, game completion rate, and more. GameHoarder tracks this information about your collection and displays it to you in a visually pleasing and informative way.</p>
        </div>
      </div>

      <hr class="featurette-divider">

      <div class="row featurette" id="feat2">
        <div class="col-md-10">
          <h2 class="featurette-heading">Recommendations <span class="text-muted">Expand your collection</span></h2>
          <p class="lead">Get recommendations for games to add to your collection based on the games you own. GameHoarder will track information about your game collection and provide you with recommendations based on the genres, developers or generations of games you prefer.</p>
        </div>
      </div>

      <hr class="featurette-divider">
      <!-- /END THE FEATURETTES -->


      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2014 GameHoarder; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>

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

