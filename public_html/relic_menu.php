<?php

    function logged() {
    return !($_SESSION["username"] == null || $_SESSION["username"] == "");
    }
    
    //echo "<p>";
    if(!logged()) echo "Nie jesteś zalogowany | <a href='relic_login.php'>Zaloguj</a>";
    else echo "Zalogowany jako: ".$_SESSION["username"]." | <a href='relic_login.php'>Wyloguj</a>";
    
    //echo "<div style='background-color: #eee; border: 2px solid black'>";
    echo "<hr>";
    echo "<h1>Wycieczka po zabytkach</h1>";
    echo "
    <table class='menu'>
        <tr>
        <th class='menu'><a class='menu' href='relic_index.php'>START</a></th>
        <th class='menu'><a class='menu' href='relic_list.php'>ZABYTKI</a></th>
        <th class='menu'><a class='menu' href='relic_trip.php'>PLAN</a></th>
        </tr>
    </table>";
    echo "<hr>";
    //echo "</div>";
?>