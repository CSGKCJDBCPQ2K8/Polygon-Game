<?php
	$charset = "ABCDEFGHIJKLMNOPRSTUWY";
	$vowelset = "AIUEO";
	$secret = file_get_contents("data/secret");
	$message = "";
	
	function checkword($word){
		global $ws, $guessedwords, $pg, $message;

		$word = strtoupper($word);
		
		if(strlen($word) < 3){
			$message = "Is less than 3 letters long";
			return false;
		}
		
		if( strpos(file_get_contents("data/wordsEn.txt"),"\n".strtoupper($word)."\r\n") === false){
			$message = "Is not a real word";
			return false;
		}
		
		if(in_array(strtoupper($word), $guessedwords)){
			$message = "You have already got this word";
			$pg = true;
			return false;
		}

		if(strpos(strtoupper($word), strtoupper($ws[0])) === false){
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
?>