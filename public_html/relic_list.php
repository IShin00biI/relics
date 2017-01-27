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
    $frelic_id = $frelic_name = $frelic_street = $frelic_reg_no = $fplace_name = $fcommune_name = $fdistrict_name = $fvoivodeship_name ="";
    $fcurr_page = 1;
    if(isset($_GET["frelic_id"])) $frelic_id = htmlspecialchars($_GET["frelic_id"]);
    if(isset($_GET["frelic_name"])) $frelic_name = htmlspecialchars($_GET["frelic_name"]);
    if(isset($_GET["frelic_street"])) $frelic_street = htmlspecialchars($_GET["frelic_street"]);
    if(isset($_GET["frelic_reg_no"])) $frelic_reg_no = htmlspecialchars($_GET["frelic_reg_no"]);
    if(isset($_GET["fplace_name"])) $fplace_name = htmlspecialchars($_GET["fplace_name"]);
    if(isset($_GET["fcommune_name"])) $fcommune_name = htmlspecialchars($_GET["fcommune_name"]);
    if(isset($_GET["fdistrict_name"])) $fdistrict_name = htmlspecialchars($_GET["fdistrict_name"]);
    if(isset($_GET["fvoivodeship_name"])) $fvoivodeship_name = htmlspecialchars($_GET["fvoivodeship_name"]);
    if(isset($_GET["fcurr_page"])) {
        $fcurr_page = htmlspecialchars($_GET["fcurr_page"]);
    }
?>
    
<form id="relic_search_form" method="GET" action=<?php echo "'".htmlspecialchars($_SERVER["PHP_SELF"])."'";?> >
    <br>
    Identyfikator w bazie: <input type="number" name="frelic_id" min="0" max="100000" value=<?php echo "'$frelic_id'";?> ><br>
    Nazwa: <input type="text" name="frelic_name" value=<?php echo "'$frelic_name'";?> ><br>
    Adres: <input type="text" name="frelic_street" value=<?php echo "'$frelic_street'";?> ><br>
    Numer w rejestrze: <input type="text" name="frelic_reg_no" value=<?php echo "'$frelic_reg_no'";?> ><br>
    Miejscowość: <input type="text" name="fplace_name" value=<?php echo "'$fplace_name'";?> ><br>
    Gmina: <input type="text" name="fcommune_name" value=<?php echo "'$fcommune_name'";?> ><br>
    Powiat: <input type="text" name="fdistrict_name" value=<?php echo "'$fdistrict_name'";?> ><br>
    Województwo:
    <select name="fvoivodeship_name">
        <option value=""></option>
        <?php 
            $db_handle2 = new SQLite3("../relic_db_folder/relic_db.db");
            $voivodes_result = $db_handle2->query("SELECT DISTINCT voivodeship_name FROM place;");
            if($voivodes_result === false) {
                die("Error in voivodeships query");
                $db_handle2->close();
            }
            elseif($voivodes_result !== true) {
                while($new_voi = $voivodes_result->fetchArray()) {
                    if($new_voi["voivodeship_name"] === $fvoivodeship_name) $selection="selected";
                    else $selection="";
                    echo "<option value='".$new_voi["voivodeship_name"]."' $selection>".$new_voi["voivodeship_name"]."</option>";
                }
            }
            $db_handle2->close();
        ?>
    </select>
    <br>
    <input type="submit" name="submit" value="Szukaj">
</form>
    <hr>
    
    <?php
            $page_limit = 100;
            $db_handle2 = new SQLite3("../relic_db_folder/relic_db.db");
            $count_query = " FROM relic AS a NATURAL JOIN place AS b WHERE ";
            if($frelic_id != "") $count_query .= "a.relic_id == $frelic_id ";
            else {
                $count_query .= "a.relic_name LIKE '%$frelic_name%' AND 
                a.relic_reg_no LIKE '%$frelic_reg_no%' "; 
                if($frelic_street !== "") $count_query .= " AND a.relic_street LIKE '%$frelic_street%' ";
                if($fplace_name !== "") $count_query .= " AND b.place_name == '$fplace_name' ";
                if($fcommune_name !== "") $count_query .= " AND b.commune_name == '$fcommune_name' ";
                if($fdistrict_name !== "") $count_query .= " AND b.district_name == '$fdistrict_name' ";
                if($fvoivodeship_name !== "") $count_query .= " AND b.voivodeship_name == '$fvoivodeship_name' ";
            }
            $print_query = "SELECT * ".$count_query;
            
            $count_query .= ";";
            $count_query = "SELECT COUNT(*) ".$count_query;
            $count_result = $db_handle2->querySingle($count_query);
            if($count_result === false) {
                $db_handle2->close();
                die("Error in count query");
            }
            $number_of_pages = floor($count_result / $page_limit) + 1;
            if($fcurr_page > $number_of_pages) $fcurr_page = 1;
            
        ?>
    Page: <input type="number" name="fcurr_page" form="relic_search_form" min="1"
        max=<?php echo "'$number_of_pages'"; ?> value=<?php echo "'$fcurr_page'" ?> > / <?php echo "$number_of_pages"; ?>
    
    <input type="submit" name="idz" form="relic_search_form" value="Idź">
    <br>
    
    <table class="relic">
        
        <tr class="relic">
            <th class="relic">Id</th>
            <th class="relic">Nazwa</th>
            <th class="relic">Nr w rejestrze</th>
            <th class="relic">Miejscowość</th>
            <th class="relic">Województwo</th>
        </tr>
        
        <?php 
            $limit_start = ($fcurr_page - 1) * 100;
            $print_query .= " LIMIT $limit_start, $page_limit ;";
            $print_result = $db_handle2->query($print_query);
            if($print_result === false) {
                $db_handle2->close();
                die("Error in print query");
            }
            elseif($print_result !== true) {
                while($print_row = $print_result->fetchArray()) {
                    echo "<tr class='relic'>";
                        echo '<td class="relic">'.$print_row["relic_id"].'</td>';
                        echo '<td class="relic">'.
                            "<a href='relic_page.php?frelic_id=".$print_row["relic_id"]."'>".$print_row["relic_name"].'</a></td>';
                        echo '<td class="relic">'.$print_row["relic_reg_no"].'</td>';
                        echo '<td class="relic">'.$print_row["place_name"].'</td>';
                        echo '<td class="relic">'.$print_row["voivodeship_name"].'</td>';
                    echo "</tr>";
                }
            }
            
        ?>
        
    </table>
</form>

<?php
    $db_handle2->close();
    require "relic_bottom.php";
?>

</body>

</html>