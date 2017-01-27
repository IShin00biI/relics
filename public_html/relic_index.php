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
    <h2>Witaj</h2>
    
    <p>Niniejsza strona przeznaczona jest do wyszukiwania polskich zabytków oraz tworzenia planu wizyt w tych miejscach. Jeśli chcesz układać plan, musisz się zalogować swoją nazwą użytkownika.</p>
    
    <p>Dane pochodzą z serwisu <a href="http://www.otwartezabytki.pl">Otwarte zabytki<a>.</p>
    
<?php
    require "relic_bottom.php";
?>

</body>

</html>