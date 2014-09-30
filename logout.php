<?php
    session_start();
    session_destroy();
    echo "<h4>session stopped at:". date('l'). date('H:i'). "hrs</h4>";
    echo "<p>you have been logged out<br>". "<a href='index.php'>Click</a>Here to login</p>";
?>
