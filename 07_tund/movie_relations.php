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
	require_once("fnc_movie.php");
    require_once("fnc_general.php");
    
    $notice = null;
    $role = null;
    $selected_person = null;
    $selected_movie = null;
    $selected_position = null;
    
    
    require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="home.php">Avaleht</a></li>
    </ul>
	<hr>
    <h2>Filmi info seostamine</h2>
    <h3>Film, inimene ja tema roll</h3>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="person_input">Isik: </label>
        <select name="person_input">
            <option value="" selected disabled>Vali isik</option>
            <?php echo read_all_person($selected_person); ?>
        </select>
        <label for="movie_input"> Film: </label>
        <select name="movie_input">
            <option value="" selected disabled>Vali film</option>
            <?php echo read_all_movie($selected_movie); ?>
        </select>
        <label for="position_input"> Amet: </label>
        <select name="position_input">
            <option value="" selected disabled>Vali amet</option>
            <?php echo read_all_position($selected_position); ?>
        </select>
        <label for="role_input"> Roll: </label>
        <input type="text" name="role_input" id="role_input" placeholder="Tegelase nimi" value="<?php echo $role; ?>">
        
        <input type="submit" name="person_in_movie_submit" value="Salvesta">
    </form>
    <span><?php echo $notice; ?></span>
</body>
</html>