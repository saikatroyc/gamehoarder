<?php
/*
 * this file contains all methods to access the gamehoarder db
 */

/*
* Code for inserting an entry into game-user relation.
*/
if(isset($_GET['insertuser']) && isset($_GET['insertgame']) && isset($_GET['insertplatform'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['insertuser'];
    $user_record['game'] = $_GET['insertgame'];
    $user_record['platform'] = $_GET['insertplatform'];
    $user_record['date'] = date('Y-m-d');
    func_insert_game_user($conn, $user_record);
    echo $user_record['game'];
}

/*
* Code for deleting an entry from game-user relation.
*/
if(isset($_GET['deleteuser']) && isset($_GET['deletegame']) && isset($_GET['deleteplatform'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['deleteuser'];
    $user_record['game'] = $_GET['deletegame'];
    $user_record['platform'] = $_GET['deleteplatform'];
    func_delete_game_user($conn, $user_record);
    echo $user_record['game'];
}

/*
* Code for rating a game.
*/
if(isset($_GET['rateuser']) && isset($_GET['rategame']) && isset($_GET['rateplatform'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['rateuser'];
    $user_record['game'] = $_GET['rategame'];
    $user_record['platform'] = $_GET['rateplatform'];
    $user_record['rating'] = $_GET['raterating'];
    func_rate_game_user($conn, $user_record);
    echo $user_record['game'];
}

/*
* Code for undoing game rating.
*/
if(isset($_GET['unrateuser']) && isset($_GET['unrategame']) && isset($_GET['unrateplatform'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['unrateuser'];
    $user_record['game'] = $_GET['unrategame'];
    $user_record['platform'] = $_GET['unrateplatform'];
    func_unrate_game_user($conn, $user_record);
    echo $user_record['game'];
}
/* add start date to a game */
if(isset($_GET['startgameuser']) && isset($_GET['startgame']) && isset($_GET['startplatform'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['startgameuser'];
    $user_record['game'] = $_GET['startgame'];
    $user_record['platform'] = $_GET['startplatform'];
    func_update_game_startdate($conn,$user_record['name'],$user_record['game'], $user_record['platform']);
    echo $user_record['game'];
}

/* add end date to a game */
if(isset($_GET['endgameuser']) && isset($_GET['endgame']) && isset($_GET['endplatform'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['endgameuser'];
    $user_record['game'] = $_GET['endgame'];
    $user_record['platform'] = $_GET['endplatform'];
    func_update_game_enddate($conn,$user_record['name'],$user_record['game'], $user_record['platform']);
    echo $user_record['game'];
}

/**
 check if the game is in list
*/
function func_isGameInList($conn,$username, $game, $platform) {
    if ($conn) {
        try {
            $s = $conn->prepare("select * from OwnsGames where game='$game' and platform='$platform' and username='$username'");
            $s->execute();
            $result=$stmt->fetchAll();
            print_r($result);
            return count($result);
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    } return 0;
}
/**
 * helper function to add end date for a game
 */
function func_update_game_enddate($conn,$username, $game, $platform) {
    // assoc array passed as input
    if ($conn) {
        try {
            $enddate = date('Y-m-d');
            $s = $conn->prepare("UPDATE OwnsGames SET enddate = '$enddate' WHERE username = '$username' AND game = '$game' AND platform='$platform'");
            $s->execute();
            $s1 = $conn->prepare("INSERT INTO UserHistory (eventtime, username, game, action, date) value (NOW(), '$username', '$game', 2, '$enddate')");
            $s1->execute();
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}
/**
 * helper function to add start date for a game
 */
function func_update_game_startdate($conn,$username, $game, $platform) {
    // assoc array passed as input
    if ($conn) {
        try {
            $startdate = date('Y-m-d');
            $s = $conn->prepare("UPDATE OwnsGames SET startdate = '$startdate' WHERE username = '$username' AND game = '$game' AND platform='$platform'");
            $s->execute();
            $s1 = $conn->prepare("INSERT INTO UserHistory (eventtime, username, game, action, date) value (NOW(), '$username', '$game', 1, '$startdate')");
            $s1->execute();
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/**
 * Function to insert entry into game-user relation
 *
 * @param
 * $conn - PHP connection Object for current DB
 * $user_record - user record containing the username and game of the entry to insert
 */
function func_insert_game_user($conn, $user_record) {
    // assoc array passed as input
    if ($conn) {
        try {
            $s = $conn->prepare("INSERT INTO OwnsGames (username, game, platform, adddate) value (:name, :game, :platform, :date)");
            $s->execute($user_record);
            $username = $user_record['name'];
            $currdate = $user_record['date'];
            $game = $user_record['game'];
            $s1 = $conn->prepare("INSERT INTO UserHistory (eventtime, username, game, action, date) value (NOW(), '$username', '$game', 0, '$currdate')");
            $s1->execute();
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/*
* Function to delete entry from game-user relation
*
* @param
* $conn - PHP Connection Object for current DB
* $user_record - user record containing the username and game of the entry to delete
*/
function func_delete_game_user($conn, $user_record) {
    if ($conn) {
        try {
            $stmt = $conn->prepare("DELETE FROM OwnsGames WHERE username=:name AND game=:game AND platform=:platform");
            $stmt->execute($user_record);
            $username = $user_record['name'];
            $currdate = date('Y-m-d');
            $game = $user_record['game'];
            // log delete event in UserHistory
            $s1 = $conn->prepare("INSERT INTO UserHistory (eventtime, username, game, action, date) value (NOW(), '$username', '$game', 4, '$currdate')");
            $s1->execute();
        } catch (PDOException $e) {
            echo "Could not delete record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/*
* Function to rate game
*
* @param
* $conn - PHP Connection Object for current DB
* $user_record - user record containing the username, game, and rating of the entry to update
*/
function func_rate_game_user($conn, $user_record) {
    if ($conn) {
        try {
            $stmt = $conn->prepare("UPDATE OwnsGames SET rating=:rating WHERE username=:name AND game=:game AND platform=:platform");
            $stmt->execute($user_record);
            $username = $user_record['name'];
            $currdate = date('Y-m-d');
            $game = $user_record['game'];
            // log delete event in UserHistory
            $s1 = $conn->prepare("INSERT INTO UserHistory (eventtime, username, game, action, date) value (NOW(), '$username', '$game', 5, '$currdate')");
            $s1->execute();
        } catch (PDOException $e) {
            echo "Could not update record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/*
* Function to unrate game
*
* @param
* $conn - PHP Connection Object for current DB
* $user_record - user record containing the username and game of the entry to update
*/
function func_unrate_game_user($conn, $user_record) {
    if ($conn) {
        try {
            $stmt = $conn->prepare("UPDATE OwnsGames SET rating=NULL WHERE username=:name AND game=:game AND platform=:platform");
            $stmt->execute($user_record);
        } catch (PDOException $e) {
            echo "Could not update record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/**
 * this is a helper fucntion that returns the rating of a game
 * as an aggregate of all the ratings in OwnsGames
 */
function func_getGameRating($conn, $game) {
    // assoc array passed as input
    $result=NULL;
    $op=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("SELECT AVG(rating) FROM OwnsGames WHERE game='$game'");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $op=$result[0][0];
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $op;

}

/**
 * this is a helper fucntion that returns the top most trending
 * games, based on number of users who have the game in their repo
 */
function func_getTopGamesByUsers($conn, $username, $count) {
    // assoc array passed as input
    $result=NULL;
    $op=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("select Game, Platform, usercount from (select game as Game, count(*) as usercount from OwnsGames where Game not in (select game from OwnsGames where username = '$username') group by Game limit $count) as PopularGame, Games where PopularGame.Game = Games.name order by usercount DESC");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row[0];
                $op[$count]['platform'] = $row[1];
                $op[$count]['count'] = $row[1];
                $count+=1;
            }
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    //print_r($op);
    return $op;

}

/**
 * this is a helper fucntion that returns the top most trending
 * games, based on number of users who have the game in their repo
 */
function func_getTopGamesByUsersPlatform($conn, $username, $count) {
    // assoc array passed as input
    $result=NULL;
    $op=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("select name, platform from Games where platform in (select platform from OwnsGames where username='$username' group  by platform order by count(*) desc) and name not in (select OwnsGames.game from OwnsGames where username='$username') limit $count");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row[0];
                $op[$count]['platform'] = $row[1];
                $count+=1;
            }
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $op;

}

/**
 * this is a function to return recommendations
 */
function func_getRecommendations($conn, $username, $count) {
    // assoc array passed as input
    $result=NULL;
    $op=NULL;
    if ($conn) {
        try {
            // 
            $stmt = $conn->prepare("SELECT Pop.name AS game, Gen.Platform AS Platform1, (Gen.gencount + Pop.usercount) AS score
                FROM
                (SELECT Games.genre AS genre, Games.platform as Platform, COUNT(Games.name) AS gencount
                FROM OwnsGames, Games
                WHERE OwnsGames.username = '$username' AND OwnsGames.game = Games.name
                GROUP BY genre, Platform) AS Gen,

                (SELECT OwnsGames.game AS name, Games.genre AS genre, COUNT(*) AS usercount
                FROM OwnsGames, Games
                WHERE OwnsGames.game = Games.name
                GROUP BY OwnsGames.game) AS Pop
                
                WHERE Gen.genre = Pop.genre AND Pop.name NOT IN (select OwnsGames.game FROM OwnsGames where OwnsGames.username = '$username')
                ORDER BY score DESC
                LIMIT $count");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row['game'];
                $op[$count]['score'] = $row['score'];
                $op[$count]['platform'] = $row['Platform1'];
                $count+=1;
            }
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    //print_r($op);
    return $op;

}

/**
 * this is a generic function that returns games by its current status.
 * status can be inrepo(0), started(1), finished(2)
 */
function func_getGamesByStatus($conn, $username, $status) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT game FROM OwnsGames WHERE username='$username' AND status='$status'");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;

}

/**
 * This is a helper fucntion to get user history
 */
function func_getUserHistory($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("SELECT * FROM UserHistory WHERE username = '$username' ORDER BY eventtime");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;
}

/**
 * Get user game count
 */
function func_getUserGameCount($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("SELECT COUNT(*) FROM OwnsGames WHERE username = '$username'");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;
}

/**
 * Get user platform count
 */
function func_getUserPlatformCount($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("SELECT COUNT(*) FROM (SELECT DISTINCT platform FROM OwnsGames WHERE username = '$username') AS platforms");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;
}

function func_getUserCountByGenre($conn, $user) {
    $result=NULL;
    $op=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT genre, count(*) as Count FROM OwnsGames JOIN Games ON OwnsGames.game=Games.name WHERE username = '$user' group by genre");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $count = 0;
            foreach($result as $row) {
                $op[$count]['genre']=$row[0];
                $op[$count]['genrecount']=$row[1];
                $count++; 
            }             
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $op;
}

function func_getUserAvgRating($conn, $user) {
    $result=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT AVG(rating) FROM OwnsGames WHERE username = '$user'");
            $stmt->execute();
            $result=$stmt->fetchAll();           
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
            //print_r($op);
    return $result;
}

/*
this function returns the number of games complete, started or just in repo
*/
function func_getGameCompletionStat($conn, $user) {
    $result=NULL;
    $op = NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT count(*) FROM OwnsGames WHERE username = '$user' and startdate is not NULL and enddate is NULL");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $op['inprogress']=$result[0];
            $result = NULL;
            $stmt = $conn->prepare("SELECT count(*) FROM OwnsGames WHERE username = '$user' and startdate is not NULL and enddate is not NULL");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $op['complete']=$result[0];
            $result = NULL;
            $stmt = $conn->prepare("SELECT count(*) FROM OwnsGames WHERE username = '$user' and startdate is NULL and enddate is NULL");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $op['notstarted']=$result[0];
            //print_r($op);
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
        }
    }
    //print_r($op);
    return $op;
}

/*
this func returns  the count of users for the games that a current user has
*/
function func_getGamesByUserCount($conn, $username) {

    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("select game, count(*) as Count from OwnsGames as OG1 where game in  (select game from OwnsGames where username='$username') group by game order by Count DESC");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['game']=$row[0];
                $op[$count]['usercount']=$row[1];
                $count++; 
            }
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $op;
}
/**
 * Get user genre count
 */
function func_getUserGenreCount($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("SELECT COUNT(*) FROM (SELECT DISTINCT genre FROM OwnsGames JOIN Games ON OwnsGames.game=Games.name WHERE username = '$username') AS genres");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;
}

/**
 * Get user platform count
 */
function func_getUserPlatformMax($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("SELECT platform, COUNT(*) AS platcount FROM OwnsGames WHERE username = '$username' GROUP BY platform ORDER BY platcount DESC LIMIT 1");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;
}

/**
 * Get user platform count
 */
function func_getUserDeveloperMax($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("SELECT developer, COUNT(*) AS devcount FROM OwnsGames JOIN Develops ON OwnsGames.game=Develops.game WHERE username = '$username' GROUP BY developer ORDER BY devcount DESC LIMIT 1");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;
}

/**
 * Get user platform count
 */
function func_getUserFirstGame($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("SELECT game FROM OwnsGames WHERE username = '$username' ORDER BY adddate ASC LIMIT 1");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;
}

/**
 * Get user platform count
 */
function func_getUserLongestGame($conn, $username) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("SELECT game, DATEDIFF(startdate,enddate) as length FROM OwnsGames WHERE username = '$username' ORDER BY length DESC LIMIT 1");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;
}

/**
 * this is a helper function which returns
 * all the date specific stats about games
 * in a user's repo
 * code snppet to access the data on the other side

 * foreach ($game_list as $row) {
 *     echo $row['game']. $row['adddate']. "<br>";
 * }
 */
function func_getGamesDateStats($conn, $username) {
    $result=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT game, adddate, startdate, enddate FROM OwnsGames WHERE username='$username'");
            $stmt->execute();
            $result=$stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $result;

}
/*
* Function to fetch all games belonging to a specific user
*
* @param
* $conn - PHP Data Object for current DB
* $username - username of user whose games to fetch
*
* @return
* $result - array of all games belonging to a specific user
*/
function func_getGamesUser($conn, $username) {
    // assoc array passed as input
    $op=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT game, startdate, enddate, platform, rating FROM OwnsGames  WHERE username='$username'");
            $stmt->execute();
            $result=$stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['game']=$row[0];
                $op[$count]['startdate']=$row[1];
                $op[$count]['enddate']=$row[2];
                $op[$count]['platform']=$row[3];      
                $op[$count]['rating']=$row[4];      
                $count++;                
            }
        } catch (PDOException $e) {
            echo "Could not select record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
    return $op;
}

/**
 * Function to connect to a database
 *
 * @param
 * $db - name of database to connect to
 * @return
 * $conn - PDO associated with database
 */
function func_connect_db($db) {
    try {
		// set host, dbname, based on given input
        if ($db == "gamehoar_games") {
            $host   = "engr-cpanel-mysql.engr.illinois.edu";
            $user = "gamehoar_db";
            $pass = "gamehoarder411";
        } else {
            $user = "root";
            $pass = "root";
            $host = "localhost";
        }
		
		// user with access / modify privileges to database
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        ///echo "connected to db<br>";
    } catch (PDOException $e) {
        echo "Could not connect to DB.\n";
        echo "getMessage(): " . $e->getMessage () . "\n";
        $conn = NULL;
    }
    return $conn;
}

/*
* Function to check if a specific user exists
*
* @param
* $conn - PHP Connection Object for current DB
* $username - username that needs to be checked in database
*
* @return
* $ret - true if user exists, false if user doesn't exist
*/
function func_is_user_exists($conn, $username) {
    if ($conn == NULL) {
        $ret = false;
        goto end;
    }
    try {
        $stmt = $conn->prepare('SELECT password FROM Users WHERE username = :uname');
        $stmt->execute(array('uname' => $username));
        $result = $stmt->fetchAll();
        if (count($result)) {
            $ret = true;
        } else {
        $ret = false;
        }
    } catch (PDOException $e) {
        echo "Could not connect to DB.\n";
        echo "getMessage(): " . $e->getMessage () . "\n";
        $conn = NULL;
    }
end:
    return $ret;
}

function func_insert_new_user($conn, $user_record) {
    // assoc array passed as input
    if ($conn) {
        try {
            $s = $conn->prepare("INSERT INTO Users (username, password, email) value (:name, :pass, :email)");
            $s->execute($user_record);
            $username = $user_record['name'];
            $currdate = date('Y-m-d');
            $s1 = $conn->prepare("INSERT INTO UserHistory (eventtime, username, action, date) value (NOW(), '$username', 3, '$currdate')");
            $s1->execute();
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

//TBD: pass by reference
function func_closeDbConection($conn) {
    if ($conn != NULL) {
       $conn = NULL; 
    } else {
        echo "conn handle is NULL";
    }
}

/*
* Function to return a specific user's name, email, and password
*
* @param
* $conn - PHP Connection Object for current DB
* $username - username whose info the function should return
*
* @return
* $op - user's name, email, and password
*/
function func_getUserCredential($conn, $username) {
    $op=NULL;
    if ($conn == NULL) {
        return NULL;
    } else {
        try {
            // uname is a primary key, so atmost one row expected
            $stmt = $conn->prepare("SELECT * FROM Users WHERE username=:name");
            $stmt->execute(array('name' => $username));
            $result = $stmt->fetchAll();
            foreach($result as $row) {
                $op['name'] = $row[0];
                $op['passwd'] = $row[1];
                $op['email'] = $row[2];
            }
        } catch (PDOException $e) {
            echo "Could not connect to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
            $op = NULL;
        }
        return $op;
    }
}

/*
* Function to return a change user's password
*
* @param
* $conn - PHP Data Object for current DB
* $username - username whose password needs to be changes
* $newPassword - password to change to
*/
function func_changePassword($conn, $username, $newPassword) {
    if ($conn == NULL) {
        //$op = NULL;
        return NULL;    
    } else {
        try {
            $stmt = $conn->prepare("UPDATE Users SET password='$newPassword' WHERE username='$username'");
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Could not change password.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
            $op = NULL;
        }
    }
}

function func_getGames($conn, $search) {
    $op=NULL;
    if ($conn == NULL) {
        return NULL;
    } else {
        try {
            // uname is a primary key, so atmost one row expected
            $stmt = $conn->prepare("SELECT * FROM Games WHERE name LIKE ?");
            $like="%$search%";
            $stmt->execute(array($like));
            $result = $stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row[0];
                $op[$count]['genre'] = $row[1];
                $op[$count]['year'] = $row[2];
                $op[$count]['platform'] = $row[3];
                $count++;
            }
        } catch (PDOException $e) {
            echo "Could not connect to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
            $op = NULL;
        }
        return $op;
    }
}

function func_getGamesByDev($conn, $search) {
    //returns an assoc array of username password and email
    //echo "get dev called for". $search;
    $op=NULL;
    if ($conn == NULL) {
        //$op = NULL;
        return NULL;
    } else {
        try {
            // uname is a primary key, so atmost one row expected
            $stmt = $conn->prepare("SELECT Games.name, Games.rating, Games.genre, Games.year FROM Develops,Games WHERE Games.name = Develops.game AND Develops.developer LIKE ?");
            $like="$search%";
            $stmt->execute(array($like));
            $result = $stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row[0];
                $op[$count]['genre'] = $row[1];
                $op[$count]['year'] = $row[2];
                $op[$count]['platform'] = $row[3];
                $count+=1;
            }
        } catch (PDOException $e) {
            echo "Could not connect to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
            $op = NULL;
        }
        return $op;
    }
}

function func_getGameImage($conn, $search) {
    //returns the url of the cover art image for the given game
    //returns default thumbnail if image could not be retrieved
    if ($conn == NULL) {
        return "images/thumb.jpg";
    } else {
        try {
            //echo "SELECT wiki_img FROM GamesMeta WHERE game = '$search'";
            //return "images/thumb.jpg";
            $search = addslashes($search);
            $stmt = $conn->prepare("SELECT wiki_img FROM GamesMeta WHERE game = '$search'");
            $stmt->execute();
            $result = $stmt->fetchAll();
            if (sizeof($result) > 0) {
                if (!($result[0][0] == "null")) {
                    return $result[0][0];
                } else {
                    return "images/thumb.jpg";
                }
            } else {
                return "images/thumb.jpg";
            }
        } catch (PDOException $e) {
            echo "Could not connect to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
        return "images/thumb.jpg";
    }
}

/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_prepareGamesPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
//        $s = $conn->prepare("INSERT INTO Games (name, rating, genre, year, platform) value (:NAME, :RATING, :GENRE, :YEAR, :PLATFORM)");
        $s = $conn->prepare("INSERT INTO Games (name, genre, year, platform) value (:NAME, :GENRE, :YEAR, :PLATFORM)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}
/*
* Helper method for populating DB
*/
function func_populate($conn, $pdo, $arr) {
    if ($conn == NULL || $arr == NULL || $pdo == NULL) return;
    else {
        try {
            /*foreach ($arr as $key => $val) {
                echo "<br>" . $key . "=>" . $val . "</br>";
            }*/
            $pdo->execute($arr);
        } catch (PDOException $e) {
            echo "Could not insert to GamesDB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_prepareDeveloperPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Developers (name, country) value (:NAME, :COUNTRY)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}
/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_prepareDevelopsPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Develops (developer, game) value (:DEV, :GAME)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}

/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_preparePublisherPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Publishers (name, country) value (:NAME, :COUNTRY)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}
/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_preparePublishesPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Publishes (publisher, game) value (:PUB, :GAME)");
    } else if (strcmp($flag, "UPDATE") == 0) {
    }
    return $s; 
}
?>
