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
	
    require_once("../../../../config_vp_s2021.php");
	require_once("fnc_general.php");
	require_once("fnc_gallery.php");
    
    $page = 1;
    $page_limit = 5;
    $photo_count = count_public_photos(2);
    //hoolitseme, et saaks liikuda vaid legaalsetelöe lehekülgedele, mis on olemas
    if(!isset($_GET["page"]) or $_GET["page"] < 1){
        $page = 1;
    } elseif(round($_GET["page"] - 1) * $page_limit >= $photo_count){
        $page = ceil($photo_count / $page_limit);
    } else {
        $page = $_GET["page"];
    }
    
    $to_head = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' ."\n";
    $to_head .= '<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n";
    $to_head .= '<script src="javascript/modal.js" defer></script>' ."\n";
    
    require("page_header.php");
?>

    <!--Modaalakna osa galerii jaoks-->
    <div id="modalarea" class="modalarea">
        <!--Sulgemisnupp-->
        <span id="modalclose" class="modalclose">&times;</span>
        <!--pildikoht-->
        <div class="modalhorizontal">
            <div class="modalvertical">
                <p id="modalcaption"></p>
                <img id="modalimg" src="../pics/empty.png" alt="Galeriipilt">
                <br>
            </div>
        </div>
    </div>
	
    <h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="home.php">Avaleht</a></li>
    </ul>
	<hr>
    <h2>Fotogalerii</h2>
    
    <p>
        <?php
            if($page > 1){
                echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span> |' ."\n";
            } else {
                echo "<span>Eelmine leht</span> | \n";
            }
            if($page * $page_limit < $photo_count){
                echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>' ."\n";
            } else {
                echo "<span>Järgmine leht</span> \n";
            }
            
        ?>
    </p>
    <div id="gallery" class="gallery">
        <?php echo read_public_photo_thumbs($page_limit, $page); ?>
    </div>
</body>
</html>