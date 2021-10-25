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
	//require_once("fnc_photoupload.php");
    require_once("fnc_general.php");
    
    $photo_error = null;
    $photo_upload_notice = null;
    $photo_orig_upload_dir = "../upload_photos_orig/";
    
    $file_type = null;
    $file_name = null;
    $alt_text = null;
    $privacy = 1;
    $photo_filename_prefix = "vp_";
    $photo_upload_size_limit = 1024 * 1024;
    
    if(isset($_POST["photo_submit"])){
        if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
            //kas on pilt ja mis tüüpi?
            $image_check = getimagesize($_FILES["photo_input"]["tmp_name"]);
            if($image_check !== false){
                if($image_check["mime"] == "image/jpeg"){
                    $file_type = "jpg";
                }
                if($image_check["mime"] == "image/png"){
                    $file_type = "png";
                }
                if($image_check["mime"] == "image/gif"){
                    $file_type = "gif";
                }
            } else {
                $photo_error = "Valitud fail ei ole pilt!";
            }
            
            //Kas on lubatud suurusega?
            if(empty($photo_error) and $_FILES["photo_input"]["size"] > $photo_upload_size_limit){
                $photo_error .= "Valitud fail on liiga suur!";
            }
            
            //kas alt tekst on
            if(isset($_POST["alt_input"]) and !empty($_POST["alt_input"])){
                $alt_text = test_input(filter_var($_POST["alt_input"], FILTER_SANITIZE_STRING));
                if(empty($alt_text)){
                    $photo_error .= "Alternatiivtekst on lisamata!";
                }
            }
            
            if(empty($photo_error)){
                //teen ajatempli
                $time_stamp = microtime(1) * 10000;
                
                //moodustan failinime, kasutame eesliidet
                $file_name = $photo_filename_prefix ."_" .$time_stamp ."." .$file_type;
                //kopeerime pildi originaalkujul, originaalnimega vajalikku kataloogi
                if(move_uploaded_file($_FILES["photo_input"]["tmp_name"], $photo_orig_upload_dir .$file_name)){
                    $photo_upload_notice = "Originaalfoto laeti üles!";
                    //$photo_upload_notice = store_person_photo($file_name, $_POST["person_for_photo_input"]);
                } else {
                    $photo_upload_notice = "Foto üleslaadimine ei õnnestunud!";
                }
            }
        } else {
            $photo_error = "Pildifaili pole valitud!";
        }
        
        $photo_upload_notice = $photo_error;
    }
    
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
    <h2>Foto üleslaadimine</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <label for="photo_input"> Vali pildifail! </label>
        <input type="file" name="photo_input" id="photo_input">
        <br>
        <label for="alt_input">Alternatiivtekst (alt): </label>
        <input type="text" name="alt_input" id="alt_input" placeholder="alternatiivtekst" value="<?php echo $alt_text; ?>">
        <br>
        <input type="radio" name="privacy_input" id="privacy_input_1" value="1" <?php if($privacy == 1){echo " checked";} ?>>
        <label for="privacy_input_1">Privaatne (ainult mina näen)</label>
        <br>
        <input type="radio" name="privacy_input" id="privacy_input_2" value="2" <?php if($privacy == 2){echo " checked";} ?>>
        <label for="privacy_input_2">Sisseloginud kasutajatele</label>
        <br>
        <input type="radio" name="privacy_input" id="privacy_input_3" value="3" <?php if($privacy == 3){echo " checked";} ?>>
        <label for="privacy_input_3">Avalik (kõik näevad)</label>
        <br>
        <input type="submit" name="photo_submit" value="Lae pilt üles">
    </form>
    <span><?php echo $photo_upload_notice; ?></span>
</body>
</html>