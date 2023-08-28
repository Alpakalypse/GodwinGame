<?php
    session_start();


// Wenn nicht eingeloggt: Weiterleitung auf Startseite

if (!isset($_SESSION["userdata"]["username"])){
    header("Location: start.php");
    exit();
}


// Begrüßung anhand Nutzername in Session 

    echo '<p>Hallo, ' . $_SESSION["userdata"]["username"] . '!</p>';


// Wenn unten auf Seite neuer Spielmodus abgeschickt wurde (POST): Speichern in SQL-Tabelle

    if (isset($_POST["new_game"])){

        $server = "localhost";
        $user = "php-user";
        $pass = "DS5JiWHLqrYsPsJS";
        $database = "godwin_game";
        $verbindung = mysqli_connect($server, $user, $pass, $database) or die("Verbindung konnte nicht hergestellt werden.");
    
        mysqli_select_db($verbindung, $database) or die("Fehler beim Zugriff auf die gewünschte Datenbank");

        $new_game = $_POST['new_game'];


// SQL-Transaktion wird in $sql gespeichert
    // UPDATE: Bisheriger String in custom_game wird mit CONCAT_WS um Einträge und Kommas erweitert  

        $sql = "UPDATE players SET custom_game = CONCAT_WS(', ', custom_game, '{$new_game}') WHERE username = '{$_SESSION["userdata"]["username"]}'";
        $result = mysqli_query($verbindung, $sql);


// Neue SQL-Transaktion speichern
    // $_SESSION wird mit neuen Spielmodi geupdatet, damit diese gespielt werden können

        $sql = "SELECT * FROM players WHERE username ='" . $_SESSION['userdata']['username'] . "'";

        $result = mysqli_query($verbindung, $sql);
    
        $userdata = $result->fetch_assoc();
        $_SESSION["userdata"] = $userdata;
        mysqli_close($verbindung);
    }


// Logout-Button

    echo '
    <form method="POST" action="">
    <button type="submit" name="logout">Logout</button>
    </form>';

if (isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("Location: start.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="de-DE">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eigenes Spiel erstellen</title>
</head>
<body>
    <?php


// Ausgabe einer Liste mit den Spielmodi
    // Funktioniert leider atm nicht :c

    if(isset($_SESSION["userdata"]["custom_game"])) {
        echo "Eigene Spielmodi:<br><br><ul>";

        foreach($_SESSION["userdata"]["custom_game"] as $game_output){
            echo "<li>$game_output</li><br>";
        }

        echo "</ul>";
    }
    ?>


<!-- Eingabefeld für neue Spielmodi & Button zum absenden (POST)-->

<form action="" method="POST">
    <input type="text" name="new_game">
    <input type="submit" name="send_game" value="speichern">
</form>
<a href="http://localhost/myphp/Beispieldateien/PHP-Projekt/rules.php">Zurück zu den Regeln</a>
</body>
</html>