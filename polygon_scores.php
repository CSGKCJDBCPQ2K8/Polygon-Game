<head>

</head>
<body>
	<table border="1" style="width:100%">
	 <tr style="font-weight: bold;">
	 	<td>Wordset</td>
		<td>Name</td>
		<td>Score</td>
	  	<td>Words</td>
	 </tr>
		<?php
			$mysqli = new mysqli('127.0.0.1', 'root', '', 'polygon');

			if ($result = $mysqli->query("SELECT * FROM `scores` ORDER BY `score` DESC", MYSQLI_USE_RESULT)) {
				while($row = $result->fetch_assoc()) {
			        echo "<tr><td>" . $row["wordset"]. "</td><td>" . $row["name"]. "</td><td>" . $row["score"]. "</td><td>" . $row["words"]. "</td></tr>";
			    }
			}
			else
				echo "failed: ". $mysqli->error;


			$mysqli->close();
		?>
	</table>
</body>
