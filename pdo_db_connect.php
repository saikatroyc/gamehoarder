<?php
/*
 * this file contains all methods to access the gamehoarder db
 */
session_start();
if(isset($_GET['insertuser']) && isset($_GET['insertgame'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['insertuser'];
    $user_record['game'] = $_GET['insertgame'];
    $user_record['date'] = date('Y-m-d');
    func_insert_game_user($conn, $user_record);
}

/*
* Code for deleting an entry from game-user relation.
*/
if(isset($_GET['deleteuser']) && isset($_GET['deletegame'])) {
    $conn = func_connect_db("gamehoarder");
    $user_record['name'] = $_GET['deleteuser'];
    $user_record['game'] = $_GET['deletegame'];
    func_delete_game_user($conn, $user_record);
    echo $user_record['game'];
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
            $s = $conn->prepare("INSERT INTO OwnsGames (username, game, adddate) value (:name, :game, :date)");
            $s->execute($user_record);
        } catch (PDOException $e) {
            echo "Could not insert to DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/*
* Function to insert entry into game-user relation
*
* @param
* $conn - PHP Connection Object for current DB
* $user_record - user record containing the username and game of the entry to delete
*/
function func_delete_game_user($conn, $user_record) {
    if ($conn) {
        try {
            $stmt = $conn->prepare("DELETE FROM OwnsGames WHERE username=:name AND game=:game");
            $stmt->execute($user_record);
        } catch (PDOException $e) {
            echo "Could not delete record from DB.\n";
            echo "getMessage(): " . $e->getMessage () . "\n";
            $conn = NULL;
        }
    }
}

/**
 * this is a helper fucntion that returns the top most trending
 * games, based on number of users who have the game in their repo
 */
function func_getTopGamesByUsers($conn, $count) {
    // assoc array passed as input
    $result=NULL;
    if ($conn) {
        try {
            // get top $count most trending games. Trend by user count
            $stmt = $conn->prepare("select game, count(*) as usercount from OwnsGames group by game order by usercount desc limit $count");
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
            $stmt = $conn->prepare("select * from UserHistory where username = '$username' order by eventtime");
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
    $result=NULL;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT game FROM OwnsGames WHERE username='$username'");
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
 * Function to connect to a database
 *
 * @param
 * $dbname - name of database to connect to
 * @return
 * $conn - PDO associated with database
 */
function func_connect_db($dbname) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=$dbname", "root", "root");
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        //echo "connected to db<br>";
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
        $stmt = $conn->prepare('SELECT password FROM users WHERE username = :uname');
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
            $s = $conn->prepare("INSERT INTO users (username, password, email) value (:name, :pass, :email)");
            $s->execute($user_record);
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
            $stmt = $conn->prepare("SELECT * FROM users WHERE username=:name");
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
            $stmt = $conn->prepare("UPDATE users SET password='$newPassword' WHERE username='$username'");
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
            $like="$search%";
            $stmt->execute(array($like));
            $result = $stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row[0];
                $op[$count]['rating'] = $row[1];
                $op[$count]['genre'] = $row[2];
                $op[$count]['year'] = $row[3];
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
            $stmt = $conn->prepare("select Games.name, Games.rating, Games.genre, Games.year from Develops,Games where Games.name = Develops.game and Develops.developer like ?");
            $like="$search%";
            $stmt->execute(array($like));
            $result = $stmt->fetchAll();
            $count=0;
            foreach($result as $row) {
                $op[$count]['name'] = $row[0];
                $op[$count]['rating'] = $row[1];
                $op[$count]['genre'] = $row[2];
                $op[$count]['year'] = $row[3];
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

/*
* Helper method for populating DB
* This prepare statement, once created, 
* can be used multiple times
*/
function func_prepareGamesPDO($conn, $flag) {
    if (strcmp($flag, "INSERT") == 0) {
        echo "prep";
        $s = $conn->prepare("INSERT INTO Games (name, rating, genre, year) value (:NAME, :RATING, :GENRE, :YEAR)");
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
?>
