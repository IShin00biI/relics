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
    $_SESSION["username"] = "";
    $_SESSION["userid"] = 0;
    require 'relic_menu.php';
?>
    <h2>Logowanie</h2>
    
    <form method="post" action="relic_logged.php">
        <br>
        Nazwa użytkownika: <input type="text" name="username"><br>
        <input type="submit" name="submit" value="Zaloguj">
    </form>
    
<?php
    require "relic_bottom.php";
?>

</body>

</html>