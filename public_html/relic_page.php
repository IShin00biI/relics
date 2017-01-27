<?php
    //Sesja użytkownika
    session_start();
?>

<!DOCTYPE html>
<html lang="pl_PL">

<head>
<link rel="stylesheet" href="relic_style.css">
<meta charset="UTF-8">
</head>

<body>

<?php
    require "relic_menu.php";

    $relic_id = $_GET["frelic_id"];
    
    $db_handle = new SQLite3("../relic_db_folder/relic_db.db");
    
    $relic_row = $db_handle->querySingle("SELECT * FROM relic WHERE relic_id = $relic_id", true);
    
    //Gdy nie znaleziono zabytku
    if($relic_row === false || $relic_row === array() || $relic_row === null) {
        echo "<h2>Nie znaleziono zabytku!</h2>";
    }
    
    //Gdy znaleziono zabytek
    else {
        $place_row = $db_handle->querySingle("SELECT * FROM place WHERE place_id = ".$relic_row["place_id"].";", true);
        echo "<h2>Informacje o zabytku</h2>
        <p>Nazwa: ".htmlspecialchars($relic_row["relic_name"])."</p>
        <p>Identyfikator w bazie: ".htmlspecialchars($relic_id)."</p>
        <p>Numer w rejestrze: ".htmlspecialchars($relic_row["relic_reg_no"])."</p>
        <p>Datowanie: ".htmlspecialchars($relic_row["relic_dating"])."</p>
        <p>Adres: ".htmlspecialchars($relic_row["relic_street"])."</p>
        <p>Miejscowość: ".htmlspecialchars($place_row["place_name"])."</p>
        <p>Gmina: ".htmlspecialchars($place_row["commune_name"])."</p>
        <p>Powiat: ".htmlspecialchars($place_row["district_name"])."</p>
        <p>Województwo: ".htmlspecialchars($place_row["voivodeship_name"])."</p>
        <p>Opis: ".htmlspecialchars($relic_row["relic_desc"])."</p>";
    }
    
    //var_dump($relic_row);
    
    $db_handle->close();
    
    require "relic_bottom.php";
?>

</body>

</html>