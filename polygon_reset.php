<?php
    setcookie("ws", '', 1);
    setcookie("gw", '', 1);
    setcookie("vh", '', 1);

    if(isset($_POST["wordset"]))
    	header('Location: polygon.php?wordset=' . $_POST["wordset"]);
    else
    	header('Location: polygon.php');
?>
<h2>:^)</h2>