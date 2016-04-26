<?php
	$secret = file_get_contents("data/secret");
	function validityhash($c1, $c2){
		global $secret;
		return hash("sha256", $c1 . $c2. $secret);
	}
	if(isset($_COOKIE["ws"]) && isset($_COOKIE["gw"]) && isset($_COOKIE["vh"])){
		if(validityhash($_COOKIE["ws"], $_COOKIE["gw"])  === $_COOKIE['vh']){
			$mysqli = new mysqli('127.0.0.1', 'root', '', 'polygon');
			
			$prepared = $mysqli->prepare("INSERT INTO scores (wordset, name, score, words, date)  VALUES (:wordset, :name, :score, :words, :date)");
			$prepared->bindParam(':wordset', $wordset);
			$prepared->bindParam(':name', $name);
			$prepared->bindParam(':score', $score);
			$prepared->bindParam(':words', $words);
			$prepared->bindParam(':date', $date);
			
			$wordset = mysqli_real_escape_string($link, $_COOKIE["ws"]);
			$name = mysqli_real_escape_string("TEST");
			$score = count(explode(',',$_COOKIE["gw"]));
			$words = mysqli_real_escape_string($link, $_COOKIE["gw"]));
			
			$prepared->execute();
			
			$mysqli->close();
			header('Location: polygon_reset.php');
		}
		else
			echo "<h2> ERROR: FAILED VALIDITY CHECK!</h2>";
	}
	else
		echo "<h2> ERROR: MISSING COOKIES!</h2>";
?>