<?php
    //alustame sessiooni
    session_start();
    //kas on sisselogitud
    if(!isset($_SESSION["user_id"])){
        header("Location: page.php");
    }
    //väljalogimine
    if(isset($_GET["logout"])){
        session_destroy();
        header("Location: page.php");
    }
    
    //testime klassi
    //require_once("classes/Test.class.php");
    //$my_test_object = new Test(33);
    //echo "Avalik muutuja: " .$my_test_object->non_secret_value;
    //echo "Salajane muutuja: " .$my_test_object->secret_value;
    //$my_test_object->multiply();
    //$my_test_object->reveal();
    //unset($my_test_object);
    
    require("page_header.php");
?>
	<h1 id="katseabi"><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
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
    </ul>
</body>
</html>