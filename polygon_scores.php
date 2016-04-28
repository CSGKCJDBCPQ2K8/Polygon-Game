<head>

</head>
<body>
	<form method="post">
		<input type="text" name="wordset" autocomplete="off" maxlength="7" autofocus value=<?php if(isset($_COOKIE["ws"])){echo '"'.strtoupper($_COOKIE["ws"]).'"';} ?>>
		<input type="submit" value="Go">
	</form>

	<table border="1" style="width:100%">
	 <tr style="font-weight: bold;">
	 	<td>Wordset</td>
		<td>Name</td>
		<td>Score</td>
	  	<td>Words</td>
	 </tr>
		<?php
			$mysqli = new mysqli('127.0.0.1', 'root', '', 'polygon');

			//$q = "SELECT * FROM `scores` ORDER BY `score` DESC";

			//if(isset($_POST["wordset"]))
				//if($_POST["wordset"] != "")
					$q = "SELECT * FROM `scores` WHERE `wordset` = '" . $mysqli->real_escape_string($_COOKIE["ws"]) . "' ORDER BY `score` DESC";

			if ($result = $mysqli->query($q, MYSQLI_USE_RESULT)) {
				while($row = $result->fetch_assoc()) {
			        echo "<tr><td>" . $row["wordset"]. "</td><td>" . $row["name"]. "</td><td>" . $row["score"]. "</td></tr>"; //<td>" . $row["words"]. "</td>
			    }
			}
			else
				echo "failed: ". $mysqli->error;

			//echo $q;
			$mysqli->close();
		?>
	</table>
</body>
