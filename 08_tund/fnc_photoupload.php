<?php

    function save_image($image, $file_type, $target){
        $notice = null;
        
        if($file_type == "jpg"){
            if(imagejpeg($image, $target, 90)){
                $notice = "Vähendatud pildi salvestamine õnnestus!";
            } else {
                $notice = "vähendatud pildi salvestamisel tekkis tõrge!";
            }
        }
        
        if($file_type == "png"){
            if(imagepng($image, $target, 6)){
                $notice = "Vähendatud pildi salvestamine õnnestus!";
            } else {
                $notice = "vähendatud pildi salvestamisel tekkis tõrge!";
            }
        }
        
        if($file_type == "gif"){
            if(imagegif($image, $target)){
                $notice = "Vähendatud pildi salvestamine õnnestus!";
            } else {
                $notice = "vähendatud pildi salvestamisel tekkis tõrge!";
            }
        }
        
        return $notice;
    }