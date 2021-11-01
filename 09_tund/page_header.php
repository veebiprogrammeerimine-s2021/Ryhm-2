<?php
    $css_color = null;
    //<style>
    //  body {
    //     background-color: #FFFFFF;
    //     color: #000000;
    //  }
    //</style>
    $css_color .= "<style> \n";
    $css_color .= "body { \n";
    $css_color .= "\t background-color: " .$_SESSION["bg_color"] ."; \n";
    $css_color .= "\t color: " .$_SESSION["text_color"] ."; \n";
    $css_color .= "} \n";
    $css_color .= "</style> \n";
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</title>
    <?php
        echo $css_color;
        if(isset($to_head) and !empty($to_head)){
            echo $to_head;
        }
    ?>
</head>
<body>
    <img src="../pics/vp_banner.png" alt="veebiprogrammeerimise lehe bänner">