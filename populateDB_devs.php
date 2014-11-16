<?php
    /**
    *Format (tab separated): 
     name	alt_names	type	wiki_url	logo_url	location	foundation
#    parent	precedessor	successor	defunct	fate
#    founder	employees	people	equity	website
    */
	$DEVNAME = 0;
	$LOCATION = 5;
    $TOTAL_COLS = 17; 
	
    echo"<h1>populate DB</h1>";
	
    // read file
    $file_handle = fopen("devs_wiki.txt", "r");
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
			// create Developers table, maximum size of name is 64 chars
			$stmt = $conn->prepare("CREATE TABLE Developers ( Name VARCHAR(64), Country TEXT, PRIMARY KEY (Name) );");
            $stmt->execute();
			
            // prepare your pdo here
            $pdo_Developers = func_prepareDeveloperPDO($conn, "INSERT");
            while(!feof($file_handle)) {
                $line = fgets($file_handle);
                $assoc_line = explode("\t", $line);
                if (count($assoc_line) == $TOTAL_COLS) {
                    // populate developer
                    $dev['NAME'] = $assoc_line[$DEVNAME];
                    $dev['COUNTRY'] = (empty($assoc_line[$LOCATION])) ? NULL : $assoc_line[$LOCATION];
                    echo $dev['NAME']. "<br>";
                    func_populate($conn, $pdo_Developers, $dev);
                } else {
					echo("incorrect column number: $line");
				}
            }
        }
    } else {
        echo("error");
    }
    fclose($file_handle);
    $conn = NULL;
?>
