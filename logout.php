<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Logout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/respond.js"></script>
</head>
<body class="container">
<?php
    session_start();
    session_destroy();
    echo "<h4>session stopped at:". date('l'). date('H:i'). "hrs</h4>";
    echo "<p>you have been logged out<br>". "<a href='index.php'>Click</a>Here to login</p>";
?>
</body>
</html>
