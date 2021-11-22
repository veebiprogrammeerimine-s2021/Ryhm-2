<?php
    require_once("../../../../config_vp_s2021.php");
    $database = "if21_rinde";
    
    $id = null;
    $privacy = 3;
    
    if(isset($_GET["photo"]) and !empty($_GET["photo"])){
		$id = filter_var($_GET["photo"], FILTER_VALIDATE_INT);
	}
    
    $type = "image/png";
    $output = "../pics/wrong.png";
    
    if(!empty($id)){
        $conn = new mysqli($server_host, $server_user_name, $server_password, $database);
		$conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT filename from vp_photos WHERE id = ? AND privacy = ? AND DELETED IS NULL");
        echo $conn->error;
        $stmt->bind_param("ii", $id, $privacy);
        $stmt->bind_result($filename_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $output = $photo_normal_upload_dir .$filename_from_db;
            $check = getimagesize($output);
            $type = $check["mime"];
        }
        $stmt->close();
        $conn->close();
    }
    header("Content-type: " .$type);
    readfile($output);
    