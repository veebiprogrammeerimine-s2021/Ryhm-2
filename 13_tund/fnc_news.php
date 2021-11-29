<?php
	$database = "if21_rinde";
	function save_news($news_title, $news, $expire_date, $file_name){
		$response = null;
		$photo_id = null;
		//kõigepealt foto!
		//echo "SALVESTATAKSE UUDIST!";
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		if(!empty($file_name)){
			$stmt = $conn->prepare("INSERT INTO vp_newsphotos (userid, filename) VALUES(?, ?)");
			echo $conn->error;
			$stmt->bind_param("is", $_SESSION["user_id"], $file_name);
			if($stmt->execute()){
				$photo_id = $conn->insert_id;
			}
			$stmt->close();
		}
		
		//nüüd uudis ise
		$stmt = $conn->prepare("INSERT INTO vp_news (userid, title, content, photoid, expire) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issis", $_SESSION["user_id"], $news_title, $news, $photo_id, $expire_date);
		if($stmt->execute()){
			$response = "Uudis on salvestatud!";
		} else {
			$response = "Uudise salvestamine ebaõnnestus!";
		}
		$stmt->close();
		$conn->close();
		return $response;
	}
	
	function latest_news($limit){
		$news_html = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT title, content, vp_news.added, filename FROM vp_news LEFT JOIN vp_newsphotos on vp_newsphotos.id = vp_news.photoid WHERE vp_news.expire >= ? AND vp_news.deleted IS NULL GROUP BY vp_news.id ORDER By vp_news.id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("si", $today, $limit);
		$stmt->bind_result($title_from_db, $content_from_db, $added_from_db, $filename_from_db);
		$stmt->execute();
		while ($stmt->fetch()){
			$news_html .= '<div class="newsblock';
			if(!empty($filename_from_db)){
				$news_html .=" fullheightnews";
			}
			$news_html .= '">' ."\n";
			if(!empty($filename_from_db)){
				$news_html .= "\t" .'<img src="' .$GLOBALS["photo_news_upload_dir"].$filename_from_db .'" ';
				$news_html .= 'alt="' .$title_from_db .'"';
				$news_html .= "> \n";
			}
			
			$news_html .= "\t <h3>" .$title_from_db ."</h3> \n";
			$addedtime = new DateTime($added_from_db);
			$news_html .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			
			$news_html .= "\t <div>" .htmlspecialchars_decode($content_from_db) ."</div> \n";
			$news_html .= "</div> \n";
		}
		if($news_html == null){
			$news_html = "<p>Kahjuks uudiseid pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $news_html;
	}