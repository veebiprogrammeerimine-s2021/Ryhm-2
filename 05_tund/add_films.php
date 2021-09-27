<?php
	$author_name = "Andrus Rinde";
    require_once("../../../../config_vp_s2021.php");
    //echo $server_host;
    require_once("fnc_film.php");
	require_once("fnc_general.php");
    
    $film_store_notice = null;
	
	$title_input = null;
	$year_input = date("Y");
	$duration_input = 60;
	$genre_input = null;
	$studio_input = null;
	$director_input = null;
	$title_input_error = null;
	$year_input_error = null;
	$duration_input_error = null;
	$genre_input_error = null;
	$studio_input_error = null;
	$director_input_error = null;
    
    //kas püütakse salvestada
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST["film_submit"])){
			//kontrollin, et andmeid ikka sisestati
			if(!empty($_POST["title_input"])){
				$title_input = test_input(filter_var($_POST["title_input"], FILTER_SANITIZE_STRING));
			} else {
				$title_input_error = "Palun sisesta filmi pealkiri!";
			}
			if(!empty($_POST["year_input"])){
				$year_input = filter_var($_POST["year_input"], FILTER_VALIDATE_INT);
			} else {
				$year_input_error = "Palun sisesta filmi valmimisaasta!";
			}
			if(!empty($_POST["duration_input"])){
				$duration_input = filter_var($_POST["duration_input"], FILTER_VALIDATE_INT);
			} else {
				$duration_input_error = "Palun sisesta filmi kestus!";
			}
			if(!empty($_POST["genre_input"])){
				$genre_input = test_input(filter_var($_POST["genre_input"], FILTER_SANITIZE_STRING));
			} else {
				$genre_input_error = "Palun sisesta filmi žanr!";
			}
			if(!empty($_POST["studio_input"])){
				$studio_input = test_input(filter_var($_POST["studio_input"], FILTER_SANITIZE_STRING));
			} else {
				$studio_input_error = "Palun sisesta filmi tootja!";
			}
			if(!empty($_POST["director_input"])){
				$director_input = test_input(filter_var($_POST["director_input"], FILTER_SANITIZE_STRING));
			} else {
				$director_input_error = "Palun sisesta filmi lavastaja!";
			}
			if(empty($title_input_error) and empty($year_input_error) and empty($duration_input_error) and empty($genre_input_error) and empty($studio_input_error) and empty($director_input_error)){
				
				$film_store_notice = store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
			} else {
				$film_store_notice = "Osa andmeid on puudu!";
			}
		}
	}
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
    <h2>Eesti filmide lisamine andmebaasi</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="title_input">Filmi pealkiri</label>
        <input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri" value="<?php echo $title_input; ?>"><span><?php echo $title_input_error; ?></span>
        <br>
        <label for="year_input">Valmimisaasta</label>
        <input type="number" name="year_input" id="year_input" min="1912" value="<?php echo $year_input; ?>">
		<span><?php echo $year_input_error; ?></span>
        <br>
        <label for="duration_input">Kestus</label>
        <input type="number" name="duration_input" id="duration_input" min="1" value="<?php echo $duration_input; ?>" max="600">
		<span><?php echo $duration_input_error; ?></span>
        <br>
        <label for="genre_input">Filmi žanr</label>
        <input type="text" name="genre_input" id="genre_input" placeholder="žanr" value="<?php echo $genre_input; ?>">
		<span><?php echo $genre_input_error; ?></span>
        <br>
        <label for="studio_input">Filmi tootja</label>
        <input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja" value="<?php echo $studio_input; ?>">
		<span><?php echo $studio_input_error; ?></span>
        <br>
        <label for="director_input">Filmi režissöör</label>
        <input type="text" name="director_input" id="director_input" placeholder="filmi režissöör" value="<?php echo $director_input; ?>">
		<span><?php echo $director_input_error; ?></span>
        <br>
        <input type="submit" name="film_submit" value="Salvesta">
    </form>
    <span><?php echo $film_store_notice; ?></span>
</body>
</html>