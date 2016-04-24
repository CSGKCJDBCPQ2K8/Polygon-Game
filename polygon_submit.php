<?php
	$secret = file_get_contents("data/secret");

	function validityhash($c1, $c2){
		global $secret;
		return hash("sha256", $c1 . $c2. $secret);
	}

	if(isset($_COOKIE["ws"]) && isset($_COOKIE["gw"]) && isset($_COOKIE["vh"])){
		if(validityhash($_COOKIE["ws"], $_COOKIE["gw"])  === $_COOKIE['vh']){

			$mysqli = new mysqli('127.0.0.1', 'root', '', 'polygon');
			

			$template = 'INSERT INTO scores (wordset,name,score,words,date) VALUES ("%s", "%s", "%s", "%s", NOW() )';

			
			$sql = sprintf($template,
				mysqli_real_escape_string($link, $_COOKIE["ws"]),
				"Ben",
				count(explode(',',$_COOKIE["gw"])),
				mysqli_real_escape_string($link, $_COOKIE["gw"]));
				
			$mysqli->query($sql);
			
			$mysqli->close();

			header('Location: polygon_reset.php');
		}
		else
			echo "<h2> ERROR: FAILED VALIDITY CHECK!</h2>";
	}
	else
		echo "<h2> ERROR: MISSING COOKIES!</h2>";
?>
