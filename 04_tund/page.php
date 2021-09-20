<?php
	$author_name = "Andrus Rinde";
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	$full_time_now = date("d.m.Y H:i:s");
	$hour_now = date("H");
	//echo $hour_now;
	$weekday_now = date("N");
	//echo $weekday_now;
	$day_category = "ebamäärane";
	if($weekday_now <= 5){    //  <   >   <=   >=   ==   !=
		$day_category = "koolipäev";
		if($hour_now < 6 or $hour_now >= 23){
			$part_of_day = "uneaeg";
		}
		if($hour_now >= 6 and $hour_now < 8){
			$part_of_day = "valmistumine tööpäevaks";
		}
		if($hour_now >= 6 and $hour_now < 18){
			$part_of_day = "aeg töisteks toimetusteks";
		}
		if($hour_now >= 18 and $hour_now < 23){
			$part_of_day = "isiklik aeg";
		}
	} else {
		$day_category = "puhkepäev";
		if($hour_now < 9){
			$part_of_day = "uneaeg";
		}
		if($hour_now >= 9 and $hour_now < 21){
			$part_of_day = "mõnusalt vaba aeg";
		}
		if($hour_now >= 21){
			$part_of_day = "õhtune puhkeaeg";
		}
	}
	//echo $day_category;
	//kuupäev kellaaeg, on koolipäev
	
	//loeme fotode kataloogi sisu
	$photo_dir = "../photos/";
	$allowed_photo_types = ["image/jpeg", "image/png"];
	//$all_files = scandir($photo_dir);
	$all_files = array_slice(scandir($photo_dir), 2);
	//echo $all_files; jama!
	//var_dump($all_files);
	//$only_files = array_slice($all_files, 2);
	//var_dump($only_files);
	
	//sõelun välja ainult lubatud pildid
	$photo_files = [];
	foreach($all_files as $file){
		$file_info = getimagesize($photo_dir .$file);
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $file);
			}
		}
	}
	//var_dump($photo_files);
	$limit = count($photo_files);
	//echo $limit;
	$pic_num = mt_rand(0, $limit - 1);
	$pic_file = $photo_files[$pic_num];
	//<img src="pilt.jpg" alt="Tallinna Ülikool">
	$pic_html = '<img src="' .$photo_dir .$pic_file .'" alt="Tallinna Ülikool">';
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
	<img src="3700x1100_pildivalik187.jpg" alt="Tallinna Ülikooli Terra õppehoone" width="600">
	<p>Lehe avamise hetk: <span><?php echo $weekday_names_et[$weekday_now - 1] .", " .$full_time_now .", on " .$day_category .", " .$part_of_day; ?></span>.</p>
	<h2>Kursusel õpime</h2>
	<ul>
		<li>HTML keelt</li>
		<li>PHP programmeerimiskeelt</li>
		<li>SQL päringukeelt</li>
		<li>jne</li>
	</ul>
	<?php echo $pic_html; ?>
</body>
</html>