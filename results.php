<?php
session_start();


// Redirect wenn nicht eingeloggt

if (!isset($_SESSION["userdata"]["username"])){
    header("Location: start.php");
    exit();
}


// Erstellen eines Arrays $urls aus string $_POST["versuche"] mit explode()

$urls = explode(",", $_POST["versuche"]);


// Zählen der Einträge im Array zum Ermitteln der Punktzahl

$score = count($urls);


// Letztes Element des Arrays ermitteln und url decodieren (Prozentzeichen loswerden)

$last_element = end($urls);
$decypher = urldecode($last_element);
echo $decypher;


// Suchen des Begriffs innerhalb der URL um zu ermitteln ob Spieler gewonnen hat
    // Ausgabe der Schrittanzahl (weniger = besser)

if (strpos($decypher, $_SESSION["suchwort"])){
    echo '<h1 style="color:green">Glückwunsch, Du hast gewonnen!</h1>';
    echo 'Du hast ' . $score . ' Versuche gebraucht.<br><br>';
    
    
// Wenn Punktzahl kleiner als Bestpunktzahl in SESSION oder noch keine Bestpunktzahl vorhanden

    if ($score < $_SESSION["userdata"]["highscore"] || $_SESSION["userdata"]["highscore"] == NULL){


// Spieler beglückwünschen :D

echo "Das ist ein neuer Rekord :D<br><br>";


// SQL-Transaktion vorbereiten

        $server = "localhost";
        $user = "php-user";
        $pass = "DS5JiWHLqrYsPsJS";
        $database = "godwin_game";
        $verbindung = mysqli_connect($server, $user, $pass, $database) or die("Verbindung konnte nicht hergestellt werden.");
    
        mysqli_select_db($verbindung, $database) or die("Fehler beim Zugriff auf die gewünschte Datenbank");


// Punktzahl anhand Primärschlüssel username updaten

        $sql = "UPDATE players SET highscore = " . $score . " WHERE username = '{$_SESSION["userdata"]["username"]}'";
        $result = mysqli_query($verbindung, $sql);


// Ausgabe der Top 10 mit 4-Buchstaben-Kürzel

        $sql = "SELECT highscore_name, highscore FROM players ORDER BY highscore ASC LIMIT 10";
        $result = mysqli_query($verbindung, $sql);

        while($row = mysqli_fetch_assoc($result)){
            echo "<b>" . $row["highscore_name"] . " - - - - - " . "Score: " . $row["highscore"] . "<br></b>";
        }

        mysqli_close($verbindung);
    }
}


// Ausgabe wenn Suchwort nicht in URL war

else {
    echo '<h1 style="color:red">Leider falsch!</h1>'; // :c
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