<?php
	//alustame sessiooni
    session_start();
    //kas on sisselogitud
    if(isset($_SESSION["user_id"])){
  
		$database = "if21_rinde";
		
		
		require("../../../../config_vp_s2021.php");

		$photoid = intval($_GET["photo"]);
		$type = "image/png";
		$output = "../pics/wrong.png";
		$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
		$stmt = $conn->prepare("SELECT filename, userid, privacy FROM vp_photos WHERE id = ? AND deleted IS NULL");
		$stmt->bind_param("i",$photoid);
		$stmt->bind_result($filename_from_db, $userid_from_db, $privacy_from_db);
		if($stmt->execute()){
			if($stmt->fetch()){
				if($userid_from_db == $_SESSION["user_id"] or $privacy_from_db >= 2){
					$output = $photo_normal_upload_dir .$filename_from_db;
					//echo $output;
					$check = getimagesize($output);
					$type = $check["mime"];
				} else {
					$type = "image/png";
					$output = "../pics/no_rights.png";
				}
			}
		}
		$stmt->close();
		$conn->close();
		header("Content-type: " .$type);
		readfile($output);
	}