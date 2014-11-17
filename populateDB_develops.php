<?php

    /**
    *Format (tab separated): 
    Name	Year	Genre	Developer_name	Publisher_name	Systems	Regions	Rating
    */
    $GAMENAME = 0;
    $YEAR = 1;
    $GENRE = 2;
    $DEV = 3;
    $PUB = 4;
    $SYS = 5;
    $REG = 6;
    $RATING = 7; 
    $TOTAL_COLS = 27; 
    echo"<h1>populate DB</h1>";
    // read file
    $file_handle = fopen("games_wiki.txt", "r");
    if ($file_handle) {
        require_once('pdo_db_connect.php');
        $conn = func_connect_db("gamehoarder");
        echo"file handle obtained";
        $flag = false;
        while(!feof($file_handle)) {
            $line = fgets($file_handle);
            //echo "<p>" . $line . "</p>";
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
            // create publishes table
            $stmt = $conn->prepare("CREATE TABLE Publishes ( publisher VARCHAR(64), game VARCHAR(100), PRIMARY KEY (publisher, game) );");
            $stmt->execute();
            
            // prepare your pdo here
            $pdo_Games = func_prepareGamesPDO($conn, "INSERT");
            $pdo_Developers = func_prepareDeveloperPDO($conn, "INSERT");
            $pdo_Develops = func_prepareDevelopsPDO($conn, "INSERT");
            $pdo_Publishers = func_preparePublisherPDO($conn, "INSERT");
            $pdo_Publishes = func_preparePublishesPDO($conn, "INSERT");
            while(!feof($file_handle)) {
                $line = fgets($file_handle);
                $assoc_line = explode("\t", $line);
                if (count($assoc_line) == $TOTAL_COLS) {
                    $input['NAME'] = $assoc_line[$GAMENAME];
                    $input['RATING'] = (empty($assoc_line[$RATING])) ? NULL : $assoc_line[$RATING];
                    $input['GENRE'] = (empty($assoc_line[$GENRE])) ? NULL : $assoc_line[$GENRE];
                    
                    // populate developer
                    // TBD: country field
                    if (strcmp($assoc_line[$DEV], "null") !== 0) {
                        $all_devs = explode(" | ", $assoc_line[$DEV]);
                        for ($i = 0; $i < count($all_devs); $i++) {
                    	    $dev['NAME'] = $all_devs[$i];
							$dev['COUNTRY'] = NULL;
							echo $assoc_line[$GAMENAME]. $dev['NAME']. "<br>";
							func_populate($conn, $pdo_Developers, $dev);
		
							// populate Developes(developer, game) relation
							$develops['DEV'] = $all_devs[$i];
							$develops['GAME'] = $assoc_line[$GAMENAME];
							func_populate($conn, $pdo_Develops, $develops);
						}
					}
				
					// populate publisher
					// TBD: country field
					if (strcmp($assoc_line[$PUB], "null") !== 0) {
						$all_pubs = explode(" | ", $assoc_line[$PUB]);
						for ($i = 0; $i < count($all_pubs ); $i++) {
							$pub['NAME'] = $all_pubs[$i];
							$pub['COUNTRY'] = NULL;
							echo $assoc_line[$GAMENAME]. $pub['NAME']. "<br>";
							func_populate($conn, $pdo_Publishers, $pub);
		
							// populate Developes(developer, game) relation
							$publishes['PUB'] = $all_pubs[$i];
							$publishes['GAME'] = $assoc_line[$GAMENAME];
							func_populate($conn, $pdo_Publishes, $publishes);
						}
					}
                    
                }
            }
        }
    } else {
        echo("error");
    }
    fclose($file_handle);
    $conn = NULL;
?>