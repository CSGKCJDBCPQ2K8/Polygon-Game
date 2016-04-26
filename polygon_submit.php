<?php
	require "data/polygon_functions.php";

	if(isset($_COOKIE["ws"]) && isset($_COOKIE["gw"]) && isset($_COOKIE["vh"])){
		if(validityhash($_COOKIE["ws"], $_COOKIE["gw"])  === $_COOKIE['vh']){
			$mysqli = new mysqli('127.0.0.1', 'root', '', 'polygon');
			
			if($prepared = $mysqli->prepare("INSERT INTO scores (wordset, name, score, words, date)  VALUES (?, ?, ?, ?, ?)")){

				$prepared->bind_Param('ssiss', $wordset, $name, $score, $words, $date);
				
				$wordset = $mysqli->real_escape_string($_COOKIE["ws"]);
				$name = $mysqli->real_escape_string("TEST");
				$score = count(explode(',',$_COOKIE["gw"]));
				$words = $mysqli->real_escape_string($_COOKIE["gw"]);
				
				if($prepared->execute())
					echo "Success";
				else
					echo "Failed to execute statement";
				
				header('Location: polygon_reset.php');
			}
			else
				echo "Failed to prepare statement";

			$mysqli->close();
		}
		else
			echo "<h2> ERROR: FAILED VALIDITY CHECK!</h2>";
	}
	else
		echo "<h2> ERROR: MISSING COOKIES!</h2>";
?>