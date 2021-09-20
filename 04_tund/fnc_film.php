<?php 
    $database = "if21_inga_pe_T2";//kuna mul endal pole, kasutan Inga oma
    
    function read_all_films(){
        //var_dump($GLOBALS);
        //avan andmebaasiühenduse      server, kasutaja, parool, andmebaas
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        //määrame vajaliku kodeeringu
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("SELECT * FROM film");
        //igaks juhuks, kui on vigu, väljastame need
        echo $conn->error;
        //seome tulemused muutujatega
        $stmt->bind_result($title_from_db, $year_from_db, $duration_from_db, $genre_from_db, $studio_from_db, $director_from_db);
        //käsk täita
        $stmt->execute();
        //fetch()
        //<h3>pealkiri</h3>
        //<ul>
        //<li>Valmimisaasta: 1997</li>
        //...
        //</ul>
        $films_html = null;
        //while(tingimus){
            //mida teha ...
        //}
        while($stmt->fetch()){
            $films_html .= "<h3>" .$title_from_db ."</h3> \n";
            $films_html .= "<ul> \n";
            $films_html .= "<li>Valmimisaasta: " .$year_from_db ."</li> \n";
            $films_html .= "<li>Kestus: " .$duration_from_db ."</li> \n";
            $films_html .= "<li>Žanr: " .$genre_from_db ."</li> \n";
            $films_html .= "<li>Tootja: " .$studio_from_db ."</li> \n";
            $films_html .= "<li>Režissöör: " .$director_from_db ."</li> \n";
            $films_html .= "</ul> \n";
        }
        //sulgeme SQL käsu
        $stmt->close();
        //sulgeme andmebaasiühenduse
        $conn->close();
        return $films_html;
    }
    
    function store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input){
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
        $stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) values(?,?,?,?,?,?)");
        echo $conn->error;
        //seon SQL käsu päris andmetega, andmetüübid :i - integer, d - decimal, s - string
        $stmt->bind_param("siisss", $title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
        $success = null;
        if($stmt->execute()){
            $success = "Salvestamine õnnestus!";
        } else {
            $success = "Salvestamisel tekkis viga: " .$stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $success;
    }