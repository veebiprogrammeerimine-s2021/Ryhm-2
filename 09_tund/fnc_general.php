<?php
	function test_input($data) {
		$data = htmlspecialchars($data);
		$data = stripslashes($data);
		$data = trim($data);
		return $data;
	}
	
	function duration_min_to_hour_and_min($value){
		$hours_and_minutes = null;
		if($value == 1){
			$hours_and_minutes = $value ." minut";
		} elseif($value < 60){
			$hours_and_minutes = $value ." minutit";
		} else {
			$hours = floor($value / 60);
			$minutes = $value % 60;
			if($hours == 1){
				$hours_and_minutes = $hours ." tund";
			} else {
				$hours_and_minutes = $hours ." tundi";
			}
			if($minutes > 0){
				$hours_and_minutes .= " ja " .$minutes;
				if($minutes == 1){
					$hours_and_minutes .=" minut";
				} else {
					$hours_and_minutes .=" minutit";
				}
			}
		}
		return $hours_and_minutes;
	}
	
	function date_to_est_format($value){
		$temp_date = new DateTime($value);
		return $temp_date->format("d.m.Y");
	}