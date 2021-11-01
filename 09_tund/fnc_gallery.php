<?php
    $database = "if21_rinde";
    
    function show_latest_public_photo(){
        $photo_html = null;
        $privacy = 3;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos WHERE id = (SELECT MAX(id) FROM vp_photos WHERE privacy = ? AND deleted IS NULL)");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($filename_from_db, $alttext_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //<img src="kataloog.file" alt="tekst">
            $photo_html = '<img src="' .$GLOBALS["photo_normal_upload_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $photo_html .= "Üleslaetud foto";
            } else {
                $photo_html .= $alttext_from_db;
            }
            $photo_html .= '">' ."\n";
        }
        if(empty($photo_html)){
            $photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
        }
        
        $stmt->close();
		$conn->close();
		return $photo_html;
    }