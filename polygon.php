<?php
	$charset = "ABCDEFGHIJKLMNOPRSTUWY";
	$vowelset = "AIUEO";
	$ws = "";
	$secret = file_get_contents("data/secret");
	$pg = false;
	$message = "";
	
	function checkword($word){
		global $ws, $guessedwords, $pg, $message;
		
		$word = strtoupper($word);
		
		if(strlen($word) < 3){
			$message = "Is less than 3 letters long";
			return false;
		}
		
		if( strpos(file_get_contents("wordsEn.txt"),"\n".strtolower($word)."\r\n") === false){
			$message = "Is not a real word";
			return false;
		}
		
		if(in_array(strtolower($word), $guessedwords)){
			$message = "You have already got this word";
			$pg = true;
			return false;
		}

		if(strpos(strtolower($word), strtolower($ws[0])) === false){
			$message = "Middle letter is misssing";
			return false;
		}

		foreach(str_split($ws) as $letter){
			if(substr_count($ws, $letter) >= substr_count($word, $letter))
				$word = str_replace($letter, "", $word);
		}
		
		if(strlen($word) === 0){
			return true;
		}
		else{
			$message = "Cant make this word from letters";
			return false;
		}
	}
	
	function validityhash($c1, $c2){
		global $secret;
		return hash("sha256", $c1 . $c2. $secret);
	}
	
	function randchar($vowelonly = 0){
		global $charset, $vowelset;
		
		if($vowelonly == 0){
			$c = substr($charset, rand(0,strlen($charset)-1), 1);
			$charset = str_replace($c, "", $charset);
			return $c;
			
		}
		else{
			$c = substr($vowelset, rand(0,4), 1);
			$charset = str_replace($c, "", $charset);
			return $c;
		}
	}
	
	if(isset($_COOKIE['ws']) & isset($_COOKIE['vh'])) {
		if(isset($_COOKIE['gw']))
			$gw = trim($_COOKIE['gw'],",");
		else
			$gw = "";

		$ws = $_COOKIE['ws'];

		if(validityhash($ws, $gw)  !== $_COOKIE['vh']){
			echo "<h1>WRONG HASH: " . validityhash($ws, $gw) . "</h1>";
			return false;
		}
		
		$guessedwords = explode(',', $gw);
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if(checkword($_POST["word"])){
				array_push ($guessedwords, $_POST["word"]);
				$gw = trim(implode (",", $guessedwords), ",");
			}
		}
	}
	else{
		$ws = randchar(1);
		
		for ($x = 0; $x <= 5; $x++) {
			$ws .= randchar();
		}
		
		$gw = "";
	}
	
	setcookie("ws", $ws);
	setcookie("gw", $gw);
	
	setcookie("vh", validityhash($ws, $gw));
	echo validityhash($ws, $gw)
?>
<html>
	<head>
		<title>Polygon</title>
	</head>
	
	<body>
		<div id="polygon_main">
			<h1 id="polygon_game_title">Polygon</h1>

			<div id="polygon_game_container">
				<img  src="polygon_game_base.svg">
				<p class="polygon_game_char" style="left: 150; top: 210; color: white;"><?php echo $ws[0] ?></p>
				
				<p class="polygon_game_char" style="left: 150; top: 90;"><?php echo $ws[1] ?></p>
				<p class="polygon_game_char" style="right: 50; top: 150;"><?php echo $ws[2] ?></p>
				<p class="polygon_game_char" style="right: 50; top: 270;"><?php echo $ws[3] ?></p>
				<p class="polygon_game_char" style="left: 150; top: 330;"><?php echo $ws[4] ?></p>
				<p class="polygon_game_char" style="left: 45; top: 270;"><?php echo $ws[5] ?></p>
				<p class="polygon_game_char" style="left: 45; top: 150;"><?php echo $ws[6] ?></p>
			</div>

			<form method="post">
				<input class="polygon_submit" style="width: 250;" type="text" name="word" autocomplete="off" maxlength="32" autofocus>
				<input class="polygon_submit" style="width: 95;" type="submit" value="Go">
			</form>

			<?php
				if($gw !== "")
					echo "<h3 style=\"display: inline-block; margin: 5px;\">" .
						count(explode(',', $gw)) . " " . (count(explode(',', $gw)) > 1 ? "words" : "word") ."  </h3>";
			?>
			<a href="polygon_reset.php">Reset</a>
			<?php 
				if($message !== "")
					echo "<div id=\"polygon_messagebox\">" . $message . "</div>";
			?>

			<br>
			<ul>
				<?php
					if($gw !== "")
						foreach(explode(',', $gw) as $word){
							if($pg)
								echo "<li ".($word == $_POST["word"] ? " id=\"polygon_previous_guess\" " : "")."> " . $word . " </li>";
							else
								echo "<li> " . $word . " </li>";
						}
				?>
			</ul> 
		</div>
	</body>
	
	<style>
		#polygon_main {
			width: 410;
			height: 95%;
			position: absolute;
			left: 50%;
			margin-left: -210;
			padding: 5;
			top: 1.25%;
			border: solid;
			overflow: auto;
			overflow-x: hidden;
		}
		#polygon_game_title {
			width: 100%;
			text-align: center;
			font: 50px arial, serif;
			margin: 5 0 10 0;
		}
		.polygon_game_char {
			position: absolute;
			float: left;
			margin: 0;
			width: 100;
			height: 100;
			text-align: center;
			font: 75px arial, serif;
			font-weight: bold;
		}
		.polygon_submit {
			margin: 10;
			height: 50;
			font: 35px arial, serif;
			display: inline-block;
		}
		#polygon_previous_guess {
			color: red;
			font-weight: bold;
		}
		#polygon_messagebox {
			font-weight: bold;
			background-color: #ff9999;
			border-style: solid;
			margin: 10px;
			padding: 5px;
		}
		ul {
			font: 20px arial, serif;
			margin: 10px;
		}
	</style>
</html>