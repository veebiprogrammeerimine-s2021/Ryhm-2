<?php
	$database = "if21_rinde";

	function resize_photo($src, $w, $h, $keep_orig_proportion = true){
		$image_w = imagesx($src);
		$image_h = imagesy($src);
		$new_w = $w;
		$new_h = $h;
		$cut_x = 0;
		$cut_y = 0;
		$cut_size_w = $image_w;
		$cut_size_h = $image_h;
		
		if($w == $h){
			if($image_w > $image_h){
				$cut_size_w = $image_h;
				$cut_x = round(($image_w - $cut_size_w) / 2);
			} else {
				$cut_size_h = $image_w;
				$cut_y = round(($image_h - $cut_size_h) / 2);
			}	
		} elseif($keep_orig_proportion){//kui tuleb originaaproportsioone säilitada
			if($image_w / $w > $image_h / $h){
				$new_h = round($image_h / ($image_w / $w));
			} else {
				$new_w = round($image_w / ($image_h / $h));
			}
		} else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
			if($image_w / $w < $image_h / $h){
				$cut_size_h = round($image_w / $w * $h);
				$cut_y = round(($image_h - $cut_size_h) / 2);
			} else {
				$cut_size_w = round($image_h / $h * $w);
				$cut_x = round(($image_w - $cut_size_w) / 2);
			}
		}
			
		//loome uue ajutise pildiobjekti
		$my_new_image = imagecreatetruecolor($new_w, $new_h);
        //säilitame png piltide puhul läbipaistvuse
        imagesavealpha($my_new_image, true);
        $trans_color = imagecolorallocatealpha($my_new_image, 0, 0, 0, 127);
        imagefill($my_new_image, 0, 0, $trans_color);
        
		imagecopyresampled($my_new_image, $src, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
		return $my_new_image;
	}
	
	function add_watermark($image, $watermark_file){
		$watermark = imagecreatefrompng($watermark_file);
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$watermark_x = imagesx($image) - $watermark_width - 10;
		$watermark_y = imagesy($image) - $watermark_height - 10;
		imagecopy($image, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);
		imagedestroy($watermark);
	}
		
    function save_image($image, $file_type, $target){
        $notice = null;
        
        if($file_type == "jpg"){
            if(imagejpeg($image, $target, 90)){
                $notice = "salvestamine õnnestus!";
            } else {
                $notice = "salvestamisel tekkis tõrge!";
            }
        }
        
        if($file_type == "png"){
            if(imagepng($image, $target, 6)){
                $notice = "salvestamine õnnestus!";
            } else {
                $notice = "salvestamisel tekkis tõrge!";
            }
        }
        
        if($file_type == "gif"){
            if(imagegif($image, $target)){
                $notice = "salvestamine õnnestus!";
            } else {
                $notice = "salvestamisel tekkis tõrge!";
            }
        }
        
        return $notice;
    }
	
	function store_photo_data($image_file_name, $alt, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vp_photos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issi", $_SESSION["user_id"], $image_file_name, $alt, $privacy);
		if($stmt->execute()){
		  $notice = "Foto lisati andmebaasi!";
		} else {
		  $notice = "Foto lisamisel andmebaasi tekkis tõrge: " .$stmt->error;
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}