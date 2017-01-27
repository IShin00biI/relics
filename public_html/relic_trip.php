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
    require 'relic_menu.php';
?>
    <h2>Planowanie wycieczek</h2>
    
    <?php
        if(!logged()) {
            echo "<p>Zaloguj się aby skorzystać z funkcji planowania wycieczek</p>";
        }
        else {
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                if(isset($_POST["fvisit_time"]) && isset($_POST["fvisit_id"])
                    && $_POST["fvisit_time"] !== "" && $_POST["fvisit_id"] !== "") {
                    
                    $fvisit_id = htmlspecialchars($_POST["fvisit_id"]);
                    $fvisit_time = htmlspecialchars($_POST["fvisit_time"]);
                    $db_handle = new SQLite3("../relic_db_folder/relic_db.db");
                    $exist_result = $db_handle->querySingle("SELECT visit_id FROM visit WHERE visit_id == $fvisit_id;");
                    if($exist_result === false) {
                        $db_handle->close();
                        echo "die3";
                        die("Error in existance query");
                    }
                    elseif($exist_result === null) {
                        echo "Nie znaleziono szukanej wycieczki<br>";
                    }
                    else {
                        echo "Zmieniono termin<br>";
                        $db_handle->exec("UPDATE visit SET visit_time = date('$fvisit_time') WHERE visit_id == $fvisit_id;");
                    }
                    $bd_handle->close();
                    
                }
                elseif(isset($_POST["fnew_visit_time"]) && isset($_POST["frelic_id"])
                    && $_POST["fnew_visit_time"] !== "" && $_POST["frelic_id"] !== "") {
                        
                        $fnew_visit_time = htmlspecialchars($_POST["fnew_visit_time"]);
                        $frelic_id = htmlspecialchars($_POST["frelic_id"]);
                        $db_handle = new SQLite3("../relic_db_folder/relic_db.db");
                        $max_result = $db_handle->querySingle("SELECT visit_id FROM visit ORDER BY visit_id DESC LIMIT 1;");
                        $relic_exists_result = $db_handle->querySingle("SELECT relic_id FROM relic WHERE relic_id == $frelic_id;");
                        
                        if($max_result === false || $relic_exists_result === false) {
                            $db_handle->close();
                            echo "die2";
                            die("Error in maximum or relic exist query");
                        }
                        elseif($max_result === null) {
                            $new_visit_id = 1;
                        }
                        else {
                            $new_visit_id = $max_result + 1;
                        }
                        
                        if($relic_exists_result === null) {
                            echo "Nie ma takiego zabytku<br>";
                        }
                        else {
                            echo "Dodano nową wycieczkę<br>";
                            $db_handle->exec("INSERT INTO visit (visit_id, relic_id, user_id, visit_time) VALUES
                                ($new_visit_id, $frelic_id, ".$_SESSION["userid"].", date('$fnew_visit_time'));");
                        }
                        
                        $bd_handle->close();
                }
                else echo "Podano nieprawidłowe wartości<br>";
            }
            echo "<h3>Zaplanuj nową wycieczkę</h3>";
            echo "
            <form method='POST' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                Numer zabytku: <input type='number' name='frelic_id' min='1'><br>
                Data: <input type='date' name='fnew_visit_time'><br>
                <input type='submit' name='submit1' value='Dodaj'>
            </form>";
            
            
            echo "<h3>Przełóż wizytę</h3>";
            echo "
            <form method='POST' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                Numer wizyty: <input type='number' name='fvisit_id' min='1'><br>
                Nowa data: <input type='date' name='fvisit_time'><br>
                <input type='submit' name='submit2' value='Popraw'>
            </form>";
            echo "<hr>";
        
        echo '<table class="relic">
        <tr class="relic">
            <th class="relic">Id wizyty</th>
            <th class="relic">Id zabytku</th>
            <th class="relic">Nazwa zabytku</th>
            <th class="relic">Data</th>
        </tr>';
        
        $db_handle = new SQLite3("../relic_db_folder/relic_db.db");
        $visits_result = $db_handle->query(
        "SELECT * FROM visit AS a NATURAL JOIN relic AS b WHERE a.user_id == ".$_SESSION["userid"]." ORDER BY a.visit_time DESC;");
        if($visits_result === false) {
            $db_handle->close();
            echo "die1";
            die("Error in visits query");
        }
        elseif($visits_result !== true) {
            while($visit_row = $visits_result->fetchArray()) {
                echo "<tr class='relic'>
                        <th class='relic'>".$visit_row["visit_id"]."</th>
                        <th class='relic'>".$visit_row["relic_id"]."</a></th>
                        <th class='relic'>"."<a href='relic_page.php?frelic_id=".$visit_row["relic_id"]."'>".$visit_row["relic_name"]."</th>
                        <th class='relic'>".$visit_row["visit_time"]."</th>
                    </tr>";
            }
        }
        
        echo "</table>";
        $db_handle->close();
        }
    ?>
    
    
    
<?php
    require "relic_bottom.php";
?>

</body>

</html>