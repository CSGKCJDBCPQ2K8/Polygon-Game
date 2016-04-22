<?php
	$charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$vowelset = "AIUEO";
	$wordset = "";
	$secret = "tNugBZkSGXttChnKX11hiULGFyhAZtBf";
	$errormsg = "";
	$pg = false;
	
	function checkword($word){
		global $wordset, $guessedwords, $wordfile, $pg;
		
		$word = strtoupper($word);
		echo "[INPUT] (" . $word . ") <br>";
		
		if(strlen($word) < 3)
			return false;
		echo "[PASS] is greater than 3 letters <br>";
		
		if( strpos(file_get_contents("wordsEn.txt"),"\n".strtolower($word)."\r\n") !== false)
			echo "[PASS] is a real word <br>";
		else
			return false;
		
		if(in_array(strtolower($word), $guessedwords)){
			$pg = true;
			return false;
		}
		echo "[PASS] is not previously guessed <br>";
		
		echo "[PREV] " . implode(", ", $guessedwords);
		
		if(strpos($wordset[0], $word) !== false)
			return false;
		echo "<br>[PASS] contains base letter <br>";
		
		foreach(str_split($wordset) as $letter){
			if(substr_count($wordset, $letter) >= substr_count($word, $letter))
				$word = str_replace($letter, "", $word);
		}
		echo "[???] remaining letters after filter (" . $word . ")<br>";
		
		return strlen($word) === 0;
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
	
	if(isset($_COOKIE['ws']) & !isset($_POST["action"])) {
		if(!validityhash($_COOKIE['ws'], $_COOKIE['gw'])  === $_COOKIE['hash']){
			$errormsg = validityhash($_COOKIE['ws'], $_COOKIE['gw'])."<br>".$_COOKIE['ws'].$_COOKIE['gw'].$secret;
			//return false;
		}
		
		$gw = $_COOKIE['gw'];
		$ws = $_COOKIE['ws'];
		
    	$wordset = $_COOKIE['ws'];
		
		$guessedwords = explode(',', $_COOKIE['gw']);
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if(checkword($_POST["word"])){
				array_push ($guessedwords, $_POST["word"]);
				$gw = implode (",", $guessedwords);
			}
		}
	}
	else{
		$wordset = randchar(1);
		
		for ($x = 0; $x <= 5; $x++) {
			$wordset .= randchar();
		}
		
		$ws = $wordset;
		$gw = "";
		
		$ek = $wordset;
	}
	
	setcookie("ws", $ws);
	setcookie("gw", $gw);
	
	setcookie("hash", validityhash($ws, $gw));
?>
<html>
	<head>
		<title>Polygon</title>
	</head>
	
	<body>
		<?php if($errormsg !== "")
			echo "<h1>" . $errormsg . "</h1>";
		?>
		<div id="polygon_main">
			<h1 id="polygon_game_title">Polygon</h1>

			<div id="polygon_game_container">
				<img  src="polygon_game_base.svg">
				<p class="polygon_game_char" style="left: 150; top: 210; color: white;"><?php echo $wordset[0] ?></p>
				
				<p class="polygon_game_char" style="left: 150; top: 90;"><?php echo $wordset[1] ?></p>
				<p class="polygon_game_char" style="right: 50; top: 150;"><?php echo $wordset[2] ?></p>
				<p class="polygon_game_char" style="right: 50; top: 270;"><?php echo $wordset[3] ?></p>
				<p class="polygon_game_char" style="left: 150; top: 330;"><?php echo $wordset[4] ?></p>
				<p class="polygon_game_char" style="left: 45; top: 270;"><?php echo $wordset[5] ?></p>
				<p class="polygon_game_char" style="left: 45; top: 150;"><?php echo $wordset[6] ?></p>
			</div>
			<form method="post">
				<input class="polygon_submit" style="width: 250;" type="text" name="word" autocomplete="off" maxlength="32" autofocus>
				<input class="polygon_submit" style="width: 95;" type="submit" value="Go">
			</form>
			<div style="display: inline-block;">
			<?php echo "<h3>".count(explode(',', $gw))." words</h3>"; ?>
			<a href="polygon_reset.php">Reset</a>
			</div>
			<br>
			<ul>
				<?php
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
		ul {
			font: 20px arial, serif;
		}
	</style>
</html>