<?php 
    $database = "if21_inga_pe_E1";//kuna mul endal pole, kasutan Inga oma
    
    function read_all_person($selected){
        $html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Eesnimi Perekonnanimi</option>
        $stmt = $conn->prepare("SELECT id, first_name, last_name, birth_date FROM person");
        $stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $birth_date_from_db);
        $stmt->execute();
        while($stmt->fetch()){
            $html .= '<option value="' .$id_from_db .'"';
            if($selected == $id_from_db){
                $html .= " selected";
            }
            $html .= ">" .$first_name_from_db ." " .$last_name_from_db ." (" .$birth_date_from_db .")</option> \n";
        }
        $stmt->close();
        $conn->close();
        return $html;
    }
    
    function read_all_movie($selected){
        $html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Film</option>
        $stmt = $conn->prepare("SELECT id, title, production_year FROM movie");
        $stmt->bind_result($id_from_db, $title_from_db, $production_year_from_db);
        $stmt->execute();
        while($stmt->fetch()){
           $html .= '<option value="' .$id_from_db .'"'; 
           if($selected == $id_from_db){
                $html .= " selected";
            }
            $html .= ">" .$title_from_db ." (" .$production_year_from_db .")</option> \n";
        }
        $stmt->close();
        $conn->close();
        return $html;
    }
    
    function read_all_position($selected){
        $html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Film</option>
        $stmt = $conn->prepare("SELECT id, position_name FROM position");
        $stmt->bind_result($id_from_db, $position_name_from_db);
        $stmt->execute();
        while($stmt->fetch()){
           $html .= '<option value="' .$id_from_db .'"'; 
           if($selected == $id_from_db){
                $html .= " selected";
            }
            $html .= ">" .$position_name_from_db ."</option> \n";
        }
        $stmt->close();
        $conn->close();
        return $html;
    }
    
    function store_person_in_movie($selected_person, $selected_movie, $selected_position, $role){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        //<option value="x" selected>Film</option>
        $stmt = $conn->prepare("SELECT id FROM person_in_movie WHERE person_id = ? AND movie_id = ? AND position_id = ? AND role = ?");
        $stmt->bind_param("iiis", $selected_person, $selected_movie, $selected_position, $role);
        $stmt->bind_result($id_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            //selline on olemas
            $notice = "Selline seos on juba olemas!";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO person_in_movie (person_id, movie_id, position_id, role) VALUES (?, ?, ?, ?)"); 
            $stmt->bind_param("iiis", $selected_person, $selected_movie, $selected_position, $role);
            if($stmt->execute()){
                $notice = "Uus seos edukalt salvestatud!";
            } else {
                $notice = "Uue seose salvestamisle tekkis viga: " .$stmt->error;
            }
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
    
    function read_person_name_for_filename($id){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT first_name, last_name FROM person WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($first_name_from_db, $last_name_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            $notice = $first_name_from_db ."_" .$last_name_from_db;
        } else {
            $notice = $id;
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
    
    function store_person_photo($file_name, $person_id){
        $notice = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("INSERT INTO picture (picture_file_name, person_id) VALUES (?, ?)"); 
        $stmt->bind_param("si", $file_name, $person_id);
        if($stmt->execute()){
            $notice = "Uus foto edukalt salvestatud!";
        } else {
            $notice = "Uue foto andmebaasi salvestamisel tekkis viga: " .$stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
	
	function list_person_movie_info(){
		$html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT person.first_name, person.last_name, person.birth_date, person_in_movie.role, movie.title, movie.production_year, movie.duration FROM person JOIN person_in_movie ON person.id = person_in_movie.person_id JOIN movie ON movie.id = person_in_movie.movie_id");
		echo $conn->error;
		$stmt->bind_result($first_name_from_db, $last_name_from_db, $birth_date_from_db, $role_from_db, $title_from_db, $production_year_from_db, $duration_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= "<li>" .$first_name_from_db ." " .$last_name_from_db ." (sündinud: " .date_to_est_format($birth_date_from_db) ."), ";
			if(!empty($role_from_db)){
				$html .= "tegelane " .$role_from_db ." filmis " .$title_from_db ." (toodetud: " .$production_year_from_db .", kestus: " .duration_min_to_hour_and_min($duration_from_db) .")";
			} else {
				$html .= $title_from_db ." (toodetud: " .$production_year_from_db .", kestus: " .duration_min_to_hour_and_min($duration_from_db) .")";
			}
			$html .= "</li> \n";
		}
		if(empty($html)){
			$html = "<p>Info puudub.</p> \n";
		} else {
            $html = "<ul> \n" .$html ."</ul> \n";
        }
		$stmt->close();
        $conn->close();
        return $html;
	}
