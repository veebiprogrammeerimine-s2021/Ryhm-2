<?php
    require_once("../../../../config_vp_s2021.php");
    require_once("fnc_party.php");

    $notice = null;
    $code = null;
    
    //muutujad võimalike veateadetega
    $code_error = null;
    
    //kontrollime sisestust
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST["registration_submit"])){
            if(isset($_POST["code_input"]) and !empty($_POST["code_input"])){
                $code = filter_var($_POST["code_input"], FILTER_VALIDATE_INT);
            } else {
                $code_error = "Palun sisesta oma üliõpilaskood!";
            }
            
            //kui vigu pole, siis salvestame
            if(empty($code_error)){
                $notice = cancel_registration($code);
            }
            
        }//if isset lõppeb
    }//id request_method lõppeb
?>

<!DOCTYPE html>
<html lang="et">
  <head>
    <meta charset="utf-8">
	<title>Veebiprogrammeerimine</title>
  </head>
  <body>
	<h1>Veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <h2>Tühista oma peole registreerimine</h2>
		
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="code_input">üliõpilaskood:</label><br>
	  <input name="code_input" id="code_input" type="text" value="<?php echo $code; ?>"><span><?php echo $code_error; ?></span>
	  <br>
	  <input name="registration_submit" type="submit" value="Tühista"><span><?php echo $notice; ?></span>
	</form>
	<hr>
	
  </body>
</html>