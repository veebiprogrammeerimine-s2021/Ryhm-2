<?php
	$author_name = "Andrus Rinde";
    require_once("../../../../config_vp_s2021.php");
    //echo $server_host;
    require_once("fnc_film.php");
    $films_html = null;
    $films_html = read_all_films();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, veebiprogrammeerimine</title>
</head>
<body>
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <h2>Eesti filmid</h2>
    <?php echo $films_html; ?>
</body>
</html>