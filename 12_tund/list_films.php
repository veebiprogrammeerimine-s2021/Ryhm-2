<?php
    require_once("use_session.php");
	
    require_once("../../../../config_vp_s2021.php");
    //echo $server_host;
    require_once("fnc_film.php");
    $films_html = null;
    $films_html = read_all_films();
    
    require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="home.php">Avaleht</a></li>
		<li><a href="add_films.php">Filmide lisamine andmebaasi</a> versioon 1</li>
    </ul>
	<hr>
    <h2>Eesti filmid</h2>
    <?php echo $films_html; ?>
</body>
</html>