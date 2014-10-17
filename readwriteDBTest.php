<?php


$cars = array
  (
  array("Volvo",22,18),
  array("BMW",15,13),
  array("Saab",5,2),
  array("Land Rover",17,15)
  );

    echo "count 2d array:".count($cars[0]);
    require 'pdo_db_connect.php';
    $search = "far";
    $conn = func_connect_db("gamehoarder");
    if ($conn) {
        $game_list=func_getGames($conn, $search);
        print_r($game_list);
        echo "count:". count($game_list);
        for ($i = 0;$i<count($games_list);$i++) {
            echo "game:". $game_list[$i]['name'];
            echo "<br>game:". $game_list[$i]['rating'];
            echo "<br>game:". $game_list[$i]['genre'];
            echo "<br>game:". $game_list[$i]['year'];
        }
    }
    /*this file is to just test out various insert/delete/update query on DB*/
    $GAMENAME = 0;
    $YEAR = 1;
    $GENRE = 2;
    $DEV = 3;
    $PUB = 4;
    $SYS = 5;
    $REG = 6;
    $RATING = 7; 
    $TOTAL_COLS = 8; 
    echo"<h1>populate DB</h1>";
    require('pdo_db_connect.php');

    $conn = func_connect_db("gamehoarder");
                    $dev['NAME'] = $assoc_line[$DEV];
                    $dev['COUNTRY'] = NULL;
                    echo $dev['NAME']. "<br>";
                    func_populate($conn, $pdo_Developers, $dev);
    // read file
    //$file_handle = fopen("games.txt", "r");
    if ($file_handle) {
        echo"file handle obtained";
        $flag = false;
        while(!feof($file_handle)) {
            $line = fgets($file_handle);
            echo "<p>" . $line . "</p>";
            if (strpos($line,'START') !== false) {
                echo "found start";
                $flag = true;
                break;
            }
        }
        $count_nonum = 0;
        $invalid = 0;
        if ($flag) {
            $count = 0;
            while(!feof($file_handle)) {
                $line = fgets($file_handle);
                //echo "<p>" . $line . "</p>";
                $assoc_line = explode("\t", $line);
                //echo "<br>length of assocarr=" . count($assoc_line)."</br>";
                if (count($assoc_line) != 8) {
                    $invalid++;
                } else {
                    foreach ($assoc_line as $key => $val) {
                        echo "<br>" . $key . "=>" . $val . "</br>";
                    }
                    /*if (is_numeric($assoc_line[1]) == false) {
                        $int = filter_var($assoc_line[1], FILTER_SANITIZE_NUMBER_INT);
                        echo $assoc_line[1] . "----------";
                        echo "year ". $int . "</br>";
                        if (strcmp($assoc_line[1], "null") !== 0) {
                            $count_nonum++;
                        }
                    }*/
                    $count++;
                }
            }
        }
        echo "count = ". $count ." invalid = ".$invalid . "notnum = ". $count_nonum;
    } else {
        echo("error");
    }
    fclose($file_handle);
    $conn = NULL;
?>
