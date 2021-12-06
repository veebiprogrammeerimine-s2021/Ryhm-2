<?php
    require_once("../../../../config_vp_s2021.php");
    require_once("fnc_general.php");
    require_once("fnc_party.php");

    $notice = null;
    
    //kontrollime sisestust
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST["payment_submit"])){
            if(!empty($_POST["id_input"])){
				set_payment($_POST["id_input"]);
			}
		}
	}
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
	<h2>Peole on end kirja pannud</h2>
	<?php echo forms_for_payment(); ?>	
	<hr>
</body>
</html>