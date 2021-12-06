<?php
    require_once("../../../../config_vp_s2021.php");
    require_once("fnc_general.php");
    require_once("fnc_party.php");

    $notice = null;
    $name = null;
    $surname = null;
    $code = null;
    
    //muutujad võimalike veateadetega
    $name_error = null;
    $surname_error = null;
    $code_error = null;
    
    //kontrollime sisestust
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST["registration_submit"])){
            if(isset($_POST["first_name_input"]) and !empty($_POST["first_name_input"])){
                $name = test_input(filter_var($_POST["first_name_input"], FILTER_SANITIZE_STRING));
                if(strlen($name) < 1){
                    $name_error = "Palun sisesta eesnimi!";
                }
            } else {
                $name_error = "Palun sisesta eesnimi!";
            }
            
            if(isset($_POST["surname_input"]) and !empty($_POST["surname_input"])){
                $surname = test_input(filter_var($_POST["surname_input"], FILTER_SANITIZE_STRING));
                if(strlen($surname) < 1){
                    $surname_error = "Palun sisesta perekonnanimi!";
                }
            } else {
                $surname_error = "Palun sisesta perekonnanimi!";
            }
			
			if(isset($_POST["code_input"]) and !empty($_POST["code_input"])){
                $code = filter_var($_POST["code_input"], FILTER_VALIDATE_INT);
            } else {
                $code_error = "Palun sisesta oma üliõpilaskood!";
            }
            
            //kui vigu pole, siis salvestame
            if(empty($name_error) and empty($surname_error) and empty($code_error)){
                $notice = register_to_party($name, $surname, $code);
				$name = null;
				$surname = null;
				$code = null;
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
    <h2>Pane end peole kirja</h2>
		
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label for="first_name_input">Eesnimi:</label><br>
	  <input name="first_name_input" id="first_name_input" type="text" value="<?php echo $name; ?>"><span><?php echo $name_error; ?></span><br>
      <label for="surname_input">Perekonnanimi:</label><br>
	  <input name="surname_input" id="surname_input" type="text" value="<?php echo $surname; ?>"><span><?php echo $surname_error; ?></span>
	  <br>
	  <label for="code_input">üliõpilaskood:</label><br>
	  <input name="code_input" id="code_input" type="text" value="<?php echo $code; ?>"><span><?php echo $code_error; ?></span>
	  <br>
	  <input name="registration_submit" type="submit" value="Registreeri"><span><?php echo $notice; ?></span>
	</form>
	<hr>
	<h2>Peole seni registreerunuid</h2>
	<p>Kirja pannud: <?php echo registered_data(); ?> üliõpilast.</p>
	<p>Kindlaid tulijaid (maksnud): <?php echo payd_data(); ?> üliõpilast.</p>
    
  </body>
</html>