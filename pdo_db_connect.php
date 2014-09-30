<?php
/*
 * this file contains all methods to access the gamehoarder db
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
    return ($conn);
}

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
            /*foreach($result as $row) {
                print_r($row);
            }*/
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

function func_getUserCredential($conn, $username) {
    //returns an assoc array of username password and email
    //echo "get user called for :". $username;
    if ($conn == NULL) {
        //$op = NULL;
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
?>
