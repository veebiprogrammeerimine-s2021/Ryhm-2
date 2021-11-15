<?php
    class Photoupload {
        private $photo_to_upload;
        public $file_type;
        private $my_temp_image;
        private $my_new_image;
        public $error;
        public $file_name;
        
        function __construct($photo){
            $this->photo_to_upload = $photo;
            $this->error = null;
            $this->check_image();
            //$this->file_type = $type;//hiljem teeb klass selle ise kindlaks
            if(empty($this->error)){ 
                $this->my_temp_image = $this->create_image_from_file($this->photo_to_upload["tmp_name"] ,$this->file_type);
            }
        }
        
        function __destruct(){
            if(isset($this->my_temp_image)){
                @imagedestroy($this->my_temp_image);
            }
        }
        
        private function check_image(){
			$error = null;
            $image_check = getimagesize($this->photo_to_upload["tmp_name"]);
            if($image_check !== false){
                if($image_check["mime"] == "image/jpeg"){
                    $this->file_type = "jpg";
                }
                if($image_check["mime"] == "image/png"){
                    $this->file_type = "png";
                }
                if($image_check["mime"] == "image/gif"){
                    $this->file_type = "gif";
                }
                //var_dump($image_check);
            } else {
				$error = "Valitud fail ei ole pilt!";
                $this->error = $error;
            }
			return $error;
        }
        
        public function check_size($limit){
			$error = null;
            if($this->photo_to_upload["size"] > $limit){
                $error = "Valitud fail on liiga suur!";
				$this->error = $error;
            }
            return $this->error;
        }
		
		public function check_alowed_type($allowed_types){
			$error = null;
			$file_info = getimagesize($this->photo_to_upload["tmp_name"]);
			if(isset($file_info["mime"])){
				if(!in_array($file_info["mime"], $allowed_types)){
					$error = "Valitud foto fail pole lubatud tüüpi!";
					$this->error = $error;
				}
			} else {
					$error = "Valitud faili tüüpi ei õnnestu kontrollida!";
					$this->error = $error;
				}
			return $error;
		}
        
        public function create_filename($prefix){
            $time_stamp = microtime(1) * 10000;
            $this->file_name = $prefix .$time_stamp ."." .$this->file_type;
        }
        
        private function create_image_from_file($file, $file_type = "png"){
            $my_temp_image = null;
            //teen graafikaobjekti, image objekti
            if($file_type == "jpg"){
                $my_temp_image = imagecreatefromjpeg($file);
            }
            if($file_type == "png"){
                $my_temp_image = imagecreatefrompng($file);
            }
            if($file_type == "gif"){
                $my_temp_image = imagecreatefromgif($file);
            }
            
            return $my_temp_image;
        }
        
        public function resize_photo($w, $h, $keep_orig_proportion = true){
            $image_w = imagesx($this->my_temp_image);
            $image_h = imagesy($this->my_temp_image);
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
            $this->my_new_image = imagecreatetruecolor($new_w, $new_h);
            //säilitame png piltide puhul läbipaistvuse
            imagesavealpha($this->my_new_image, true);
            $trans_color = imagecolorallocatealpha($this->my_new_image, 0, 0, 0, 127);
            imagefill($this->my_new_image, 0, 0, $trans_color);
            
            imagecopyresampled($this->my_new_image, $this->my_temp_image, 0, 0, $cut_x, $cut_y, $new_w, $new_h, $cut_size_w, $cut_size_h);
        }
        
        public function add_watermark($watermark_file){
            $watermark = imagecreatefrompng($watermark_file);
            $watermark_width = imagesx($watermark);
            $watermark_height = imagesy($watermark);
            $watermark_x = imagesx($this->my_new_image) - $watermark_width - 10;
            $watermark_y = imagesy($this->my_new_image) - $watermark_height - 10;
            imagecopy($this->my_new_image, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);
            imagedestroy($watermark);
        }
        
        public function save_image($target){
            $notice = null;
            
            if($this->file_type == "jpg"){
                if(imagejpeg($this->my_new_image, $target, 90)){
                    $notice = "salvestamine õnnestus!";
                } else {
                    $notice = "salvestamisel tekkis tõrge!";
                }
            }
            
            if($this->file_type == "png"){
                if(imagepng($this->my_new_image, $target, 6)){
                    $notice = "salvestamine õnnestus!";
                } else {
                    $notice = "salvestamisel tekkis tõrge!";
                }
            }
            
            if($this->file_type == "gif"){
                if(imagegif($this->my_new_image, $target)){
                    $notice = "salvestamine õnnestus!";
                } else {
                    $notice = "salvestamisel tekkis tõrge!";
                }
            }
            imagedestroy($this->my_new_image);
            return $notice;
        }
        
        public function move_original_photo($target){
            $notice = null;
            if(move_uploaded_file($this->photo_to_upload["tmp_name"], $target)){
                    $notice .= " Originaalfoto laeti üles!";
                } else {
                    $notice .= " Foto üleslaadimine ei õnnestunud!";
                }
            return $notice;
        }
        
    }//class lõppeb