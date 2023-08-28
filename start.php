<?php
session_start();


// Alte Spielparameter löschen

unset($_SESSION["active_gamemode"]);
unset($_SESSION["standard"]);
unset($_SESSION["suchwort"]);


// Wenn sich ein Spieler registriert hat, wird hier eine SQL-Transaktion zum Eintragen des neuen nutzers gestartet

if (!empty($_POST["new_user"]) && !empty($_POST["new_password"]) && !empty($_POST["new_hi_name"])){

    $server = "localhost";
    $user = "php-user";
    $pass = "DS5JiWHLqrYsPsJS";
    $database = "godwin_game";
    $verbindung = mysqli_connect($server, $user, $pass, $database) or die("Verbindung konnte nicht hergestellt werden.");

    mysqli_select_db($verbindung, $database) or die("Fehler beim Zugriff auf die gewünschte Datenbank");

    $sql = "INSERT INTO players (`username`, `password`, `highscore_name`) VALUES ('" . $_POST['new_user'] . "', '" . $_POST['new_password'] . "', '" . $_POST['new_hi_name'] . "')";
    $result = mysqli_query($verbindung, $sql);


// Wenn Transaktion erfolgreich: Truthy => Codeblock wird ausgeführt

    if ($result){
        echo "<p>Benutzerkonto erstellt!";
    }
    else{
        echo "<p>Kontoerstellung fehlgeschlagen!";
    }

    mysqli_close($verbindung);
}
?>

<!DOCTYPE html>
<html lang="de-DE">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Willkommen bei Godwin-Game!</title>
    <link rel="stylesheet" href="css/start.css">
</head>
<body>
    <div class="grid">
        <div class="header">
            <h1>Willkommen bei der Godwin-Challenge!</h1>
        </div>
        <cite>
            <p>„Mit zunehmender Länge einer Online-Diskussion nähert sich die Wahrscheinlichkeit für einen Vergleich mit den Nazis oder Hitler dem Wert Eins an.“</p>

            <p style="text-align:right"><b>– Mike Godwin</b></p>
        </cite>
    

<?php


// Wenn keine Nutzerdaten in Session => Login-Button wird angezeigt

if (!isset($_SESSION['userdata'])){

    echo
    '<div class="login"><h2>Login</h2>
    <form method="POST" action="rules.php">
        Benutzername: <input type="text" name="login_name"><br>
        Passwort: <input type="text" name="login_password"><br>
        <input type="submit" name="login_submit" value="Einloggen">
    </form>
    <button><a href="http://localhost/myphp/Beispieldateien/PHP-Projekt/create_user.php" style="text-decoration: none; color: black">Konto erstellen</a></button></div></div>';
}


// sonst wird Spielen-Button angezeigt

else {
    echo '
    <button><a href="http://localhost/myphp/Beispieldateien/PHP-Projekt/rules.php" style="text-decoration: none; color: black">Spielen</a></button>';

    echo '
    <form method="POST" action="">
    <button type="submit" name="logout">Logout</button>
    </form></div>';
}

if (isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("Location: start.php");
    exit();
}
?>
</body>
</html>
