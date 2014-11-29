<?php

$conn = func_connect_db("gamehoarder");
$username='saikat';
$game_list=func_getGamesUser($conn, $username);
print $game_list
?>
