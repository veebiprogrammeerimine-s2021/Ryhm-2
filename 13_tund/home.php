<?php
    require_once("use_session.php");
	require_once("../../../../config_vp_s2021.php");
	require_once("fnc_news.php");
    
    //testime klassi
    //require_once("classes/Test.class.php");
    //$my_test_object = new Test(33);
    //echo "Avalik muutuja: " .$my_test_object->non_secret_value;
    //echo "Salajane muutuja: " .$my_test_object->secret_value;
    //$my_test_object->multiply();
    //$my_test_object->reveal();
    //unset($my_test_object);
    
    //küpsiste ehk cookie'de näide
    
    setcookie("vpvisitor", $_SESSION["first_name"] ." " .$_SESSION["last_name"], time() + (86400 * 9), "/~rinde/vp2021/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
    $last_visitor = null;
    if(isset($_COOKIE["vpvisitor"])){
        $last_visitor = "<p>Viimati külastas lehte: " .$_COOKIE["vpvisitor"] .".</p> \n";
    } else {
        $last_visitor = "<p>Küpsiseid ei leitud, viimane külastaja pole teada.</p> \n";
    }
    //var_dump($_COOKIE);
    
    //küpsise kustutamiseks määratakse talle varasem (enne praegust hetke) aegumine
    //time() - 3600
    
    require("page_header.php");
?>
	<h1 id="katseabi"><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <?php echo $last_visitor; ?>
    <hr>
    <ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="list_films.php">Filmide nimekirja vaatamine</a> versioon 1</li>
		<li><a href="add_films.php">Filmide lisamine andmebaasi</a> versioon 1</li>
        <li><a href="user_profile.php">Kasutajaprofiil</a></li>
        <li><a href="movie_relations.php">Filmi info sidumine</a></li>
		<li><a href="list_movie_info.php">Filmi info</a></li>
        <li><a href="gallery_photo_upload.php">Fotode üleslaadimine</a></li>
        <li><a href="gallery_public.php">Sisseloginud kasutajate jaoks avalike fotode galerii</a></li>
        <li><a href="gallery_own.php">Minu oma galerii fotod</a></li>
        <li><a href="add_news.php">Uudise lisamine</a></li>
    </ul>
	<br>
	<h2>Uudised</h2>
  <?php
	echo latest_news(5);
  ?>
</body>
</html>