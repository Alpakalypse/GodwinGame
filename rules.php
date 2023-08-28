<?php
    session_start();
 
// vorherige Spielparameter löschen

    unset($_SESSION["active_gamemode"]);
    unset($_SESSION["standard"]);
    unset($_SESSION["suchwort"]);

// Aufbau SQL-Verbindung
// Überprüfung der LOGIN-Daten

    $server = "localhost";
    $user = "php-user";
    $pass = "DS5JiWHLqrYsPsJS";
    $database = "godwin_game";
    $verbindung = mysqli_connect($server, $user, $pass, $database) or die("Verbindung konnte nicht hergestellt werden.");

    mysqli_select_db($verbindung, $database) or die("Fehler beim Zugriff auf die gewünschte Datenbank");

// Variable $sql speichert SQL-Anweisung als string

    $sql = "SELECT * FROM players WHERE username ='" . $_POST['login_name'] . "' AND password ='" .  $_POST['login_password'] . "'";
    $result = mysqli_query($verbindung, $sql); // SQL-Query mit $verbindung (Verbindungsdaten) & $sql (SQL-Anweisungsstring)

// Variable $userdata bekommt Ergebnis, $result->fetch_assoc() liefert assoziativen Array für den Datensatz aus der SQL-Tabelle

    $userdata = $result->fetch_assoc();
    $_SESSION["userdata"] = $userdata; // Eintragen des assoziativen Arrays in $_SESSION["userdata"] => Es entsteht ein 2D-Array

// Die eigens angelegten Spiemodi sind in einem langen String in der Tabelle hinterlegt, getrennt durch Kommas
// explode macht aus langem Textstring anhand der Kommas einen Array und speicher in $custom_games

    $custom_games = explode(',', $_SESSION['userdata']['custom_game']);

    mysqli_close($verbindung);

// Überprüfung ob eingeloggt; falls nein (z.B. falsche Login-Daten) zurück zu Startseite

    if (!isset($_SESSION["userdata"]["username"])){
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
    <link rel="stylesheet" href="css/rules.css">
    <title>Regeln</title>
</head>
<body>
    <div class="grid">
    <h1>Die Regeln:</h1>
    <div id="regeln">
    <p>
        Willkommen bei der Godwin Challenge! Das Spiel ist nach Godwins Gesetz (von Mike Godwin) benannt. Es besagt: Je länger eine Online-Diskussion andauert,
        desto wahrscheinlicher ist es, dass jemand einen Vergleich mit den Nazis oder dem Nationalsozialismus zieht.
    </p>
    <p>
        Das Ziel des Spiels ist es, von einer zufälligen Wikipedia-Seite aus so schnell wie möglich zur Seite für "Nationalsozialismus" zu gelangen.
    </p>
    <p>
        Wenn Du magst, kannst Du aber auch ein eigenes Suchwort eingeben.
    </p>
    <p>
        <h3>So wird gespielt:</h3>
        <ul>
            <li> Klicke auf den Start-Button</li>
            <li> Wenn Du zu einem Artikel wechseln möchtest:</li>
                <ul>
                    <li> Rechtsklicke den Artikelnamen</li>
                    <li> Kopiere den Link und füge ihn in das Textfeld ein</li>
                    <li> Klick' auf "nächste Seite"</li>
                </ul>
        </ul>
    </p>
</div>
<div id="start">
    <p><h3>
        Also, los geht's! Klicke auf den "Start"-Button für eine zufällige Wikipedia-Seite und versuche, so schnell wie möglich zur
        gesuchten Seite zu gelangen. Viel Glück!
    </h3></p></div>

    <?php

// Radio-Button für Standardspielmodus

    echo '
    <div id="buttons"><form action="godwin_game.php" method="POST">
        <input type="radio" name="standard">
        <label for="standard">Standardspiel</label><br><br>';

// Für jedes Element im $custom_games Array wird auch ein Radio-Button angelegt und der passende Name angezeigt

    foreach ($custom_games as $gamemode){
        echo
        '<input type="radio" name="spielmodus" value="' . $gamemode . '">' . ' ' . $gamemode . '<br>';
    }

// Button, um zur Seite für eigene Spielmodi zu kommen

    echo '<br><button><a href="custom.php" style="text-decoration: none; color: black">Eigenes Spiel erstellen</a></button>';
    echo '<input type="submit" value="start">';

    echo '</form></div>';

// Logout-Button

    echo '
    <form method="POST" action="">
    <button type="submit" name="logout">Logout</button>
    </form>';
    ?>

</div>
</body>
</html>