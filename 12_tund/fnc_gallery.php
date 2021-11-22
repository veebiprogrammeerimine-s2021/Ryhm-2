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
    
    function read_public_photo_thumbs($page_limit, $page){
        $gallery_html = null;
        $privacy = 2;
        $skip = ($page - 1) * $page_limit;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        //$stmt = $conn->prepare("SELECT id, filename, created, alttext FROM vp_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
        $stmt = $conn->prepare("SELECT vp_photos.id, filename, alttext, vp_photos.created, firstname, lastname, AVG(rating) as AvgValue FROM vp_photos JOIN vp_users ON vp_photos.userid = vp_users.id LEFT JOIN vp_photoratings ON vp_photoratings.photoid = vp_photos.id WHERE vp_photos.privacy >= ? AND deleted IS NULL GROUP BY vp_photos.id DESC LIMIT ?,?");
		echo $conn->error;
        $stmt->bind_param("iii", $privacy, $skip, $page_limit);
        $stmt->bind_result($id_from_db, $filename_from_db, $alttext_from_db, $created_from_db, $firstname_from_db, $lastname_from_db, $avg_rating_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            //<div class="thumbgallery">
            //<img src="kataloog.file" alt="tekst" class="thumbs" data-id="x" data-fn="see.jpg">
			//<p>Eesnimi Perekonnanimi<br>
			//Lisatud: dd.mm.yyyy</p>
			//<p id="rating44">Hinne: 3,5</p>
            //</div>
            $gallery_html .= '<div class="thumbgallery">' ."\n";
            $gallery_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $gallery_html .= "Üleslaetud foto";
            } else {
                $gallery_html .= $alttext_from_db;
            }
            $gallery_html .= '" class="thumbs" data-id="' .$id_from_db .'" data-fn="' .$filename_from_db .'">' ."\n";
			$gallery_html .= "<p>" .$firstname_from_db ." " .$lastname_from_db ."<br> \n";
			$gallery_html .= "Lisatud: " .date_to_est_format($created_from_db) ."</p> \n";
			$gallery_html .= '<p id="rating' .$id_from_db .'">';
			if(!empty($avg_rating_from_db)){
				$gallery_html .= "Hinne: " .round($avg_rating_from_db, 2);
			} else {
				$gallery_html .= "Pole hinnatud";
			}
			$gallery_html .= "</p> \n";
            $gallery_html .= "</div> \n";
        }
        if(empty($gallery_html)){
            $photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
        }
        
        $stmt->close();
		$conn->close();
		return $gallery_html;
    }
    
/*     function read_public_photo_thumbs(){
        $gallery_html = null;
        $privacy = 2;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, alttext FROM vp_photos WHERE privacy >= ? AND deleted IS NULL ORDER BY id DESC LIMIT 3,3");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($filename_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            //<img src="kataloog.file" alt="tekst">
            $gallery_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $gallery_html .= "Üleslaetud foto";
            } else {
                $gallery_html .= $alttext_from_db;
            }
            $gallery_html .= '">' ."\n";
        }
        if(empty($gallery_html)){
            $photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
        }
        
        $stmt->close();
		$conn->close();
		return $gallery_html;
    } */
    
    function read_own_photo_thumbs($page_limit, $page){
        $gallery_html = null;
        $skip = ($page - 1) * $page_limit;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT id, filename, created, alttext FROM vp_photos WHERE userid = ? AND deleted IS NULL ORDER BY id DESC LIMIT ?,?");
        echo $conn->error;
        $stmt->bind_param("iii", $_SESSION["user_id"], $skip, $page_limit);
        $stmt->bind_result($id_from_db, $filename_from_db, $created_from_db, $alttext_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            //<div class="thumbgallery">
            //<img src="kataloog.file" alt="tekst">
            //</div>
            $gallery_html .= '<div class="thumbgallery">' ."\n";
            $gallery_html .= '<a href="edit_gallery_photo.php?photo=' .$id_from_db .'">';
            $gallery_html .= '<img src="' .$GLOBALS["photo_thumbnail_upload_dir"] .$filename_from_db .'" alt="';
            if(empty($alttext_from_db)){
                $gallery_html .= "Üleslaetud foto";
            } else {
                $gallery_html .= $alttext_from_db;
            }
            $gallery_html .= '" class="thumbs">' ."\n";
            $gallery_html .= "</a> \n";
			$gallery_html .= "<p>Lisatud: " .date_to_est_format($created_from_db) ."</p> \n";
            $gallery_html .= "</div> \n";
        }
        if(empty($gallery_html)){
            $photo_html = "<p>Kahjuks avalikke fotosid üles laetud pole!</p> \n";
        }
        
        $stmt->close();
		$conn->close();
		return $gallery_html;
    }
    
    function count_public_photos($privacy){
        $photo_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_photos WHERE privacy >= ? AND deleted IS NULL");
        echo $conn->error;
        $stmt->bind_param("i", $privacy);
        $stmt->bind_result($count);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_count = $count;
        }
        $stmt->close();
		$conn->close();
		return $photo_count;
    }
	
	function count_own_photos(){
        $photo_count = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT COUNT(id) FROM vp_photos WHERE userid = ? AND deleted IS NULL");
        echo $conn->error;
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->bind_result($count);
        $stmt->execute();
        if($stmt->fetch()){
            $photo_count = $count;
        }
        $stmt->close();
		$conn->close();
		return $photo_count;
    }
	
//pildi andmete lugemine muutmiseks
	function read_own_photo($photo){
		$photo_data = [];
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename, alttext, privacy FROM vp_photos WHERE id = ? AND userid = ? AND deleted IS NULL");
		echo $conn->error;
        $stmt->bind_param("ii", $photo, $_SESSION["user_id"]);
        $stmt->bind_result($filename_from_db, $alttext_from_db, $privacy_from_db);
		$stmt->execute();
		if($stmt->fetch()){
            array_push($photo_data, true);
			array_push($photo_data, $filename_from_db);
			array_push($photo_data, $alttext_from_db);
			array_push($photo_data, $privacy_from_db);
        } else {
			array_push($photo_data, false);
		}
		$stmt->close();
		$conn->close();
		return $photo_data;
	}
	
	function photo_data_update($photo, $alttext, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("UPDATE vp_photos SET alttext = ?, privacy = ? WHERE id = ? AND userid = ?");
		echo $conn->error;
        $stmt->bind_param("siii", $alttext, $privacy, $photo, $_SESSION["user_id"]);
        if($stmt->execute()){
			$notice = "Andmete muutmine õnnestus!";
		} else {
			$notice = "Andmete muutmisel tekkis tõrge!";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function delete_photo($photo){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("UPDATE vp_photos SET deleted = NOW() WHERE id = ? AND userid = ?");
		echo $conn->error;
        $stmt->bind_param("ii", $photo, $_SESSION["user_id"]);
        if($stmt->execute()){
			$notice = "ok";
		} else {
			$notice = "Foto kustutamisel tekkis tõrge!";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}