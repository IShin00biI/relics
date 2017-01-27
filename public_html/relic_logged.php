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
    function validate($username) {
        if (strpos($username, " ") !== false || strpos($username, "&") !== false) return 0;
        return preg_match("/[a-zA-Z0-9_]+/", $username);
    }
    
    $valid_username = false;
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $valid_username = validate($username);
        if($valid_username === 1) {
                $username = $_SESSION["username"] = htmlspecialchars($username);
                $db_handle = new SQLite3("../relic_db_folder/relic_db.db");
                $userid = $db_handle->querySingle("SELECT user_id FROM user WHERE user_name == '$username';");
                if($userid === false) {
                    $valid_username = null;
                }
                elseif($userid !== null) {
                    $_SESSION["userid"] = $userid;
                }
                else {
                    $maxid = $db_handle->querySingle("SELECT user_id FROM user ORDER BY user_id DESC;");
                    $newid = 0;
                    if($maxid === false) {
                        $valid_username = null;
                    } 
                    else {
                        if($maxid !== null) {
                            $newid = $maxid;
                        }
                        ++$newid;
                        $db_handle->exec("INSERT INTO user (user_id, user_name) VALUES ($newid, '$username');");
                    }
                }
                $db_handle->close();
            }
    }
    require 'relic_menu.php';
    
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        if($valid_username === 0 || $valid_username === null) {
            echo "<h2>Logowanie nieudane</h2>";
            if($valid_username === 0) {
                echo "<p>Nazwa użytkownika może zawierać tylko litery, cyfry, znak '_'</p>";
            }
            if($valid_username === null) {
                echo "<p>Wystąpił błąd podczas logowania, spróbuj jeszcze raz</p>";
            }
        }
        else {
            echo "<h2>Logowanie udane</h2>
            <p>Zalogowany jako ".$_SESSION["username"];
        }
    }
    else {
        echo "<h2>Błędne odwołanie do strony</h2>
        <p>Przejdź na inną podstronę</p>";
    }
    
    require "relic_bottom.php";
?>

</body>

</html>
