<?php
session_start();
?>

<!DOCTYPE html>
<html lang="de-DE">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konto erstellen</title>
</head>
<body>


<!-- Formular zur Eingabe der Nutzerdaten zum Erstellen eines Kontos -->

    <form action="start.php" method="POST">
        Benutzername: <input type="text" name="new_user" maxlength="20">
        Passwort: <input type="text" name="new_password" maxlength="50">
        Name für Highscore (max. 4 Zeichen) <input type="text" name="new_hi_name" maxlength="4">
        <input type="submit" name="create_user">
    </form>
    <button><a href="start.php" style="text-decoration: none; color: black">Startseite</a></button>
</body>

</html>
