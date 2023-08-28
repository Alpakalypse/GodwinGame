<?php
session_start();


// vorherige Spielparameter löschen

unset($_SESSION["active_gamemode"]);
unset($_SESSION["standard"]);
unset($_SESSION["suchwort"]);

echo '<div class="grid">';
// redirect wenn nicht eingeloggt

if (!isset($_SESSION["userdata"]["username"])){
    header("Location: start.php");
    exit();
}


// Spielmodus bestimmen

if (isset($_POST["standard"])){
	$_SESSION["active_gamemode"] = "Nationalsozialismus";
} elseif (isset($_POST["spielmodus"])) {
    $_SESSION["active_gamemode"] = $_POST["spielmodus"];
}


// Speichern des Suchworts in der Session

echo "<h1>Suchbegriff: " . $_SESSION["active_gamemode"] . "</h1>";
$_SESSION["suchwort"] = trim($_SESSION["active_gamemode"]);


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


// Zurück zur Auswahl des Spielmodus
	// Funktioniert nicht :c

echo '
<form method="POST" action="">
<button type="submit" name="new_game">Neues Spiel</button>
</form>';

if (isset($_POST['new_game'])){
header("Location: rules.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Wikipedia-Spiel</title>
	<meta charset="UTF-8">


<!-- Konfigurieren der Darstellung der Contentbox -->

	<style type="text/css">
		body {
			font-family: Arial, sans-serif;
		}
		#content {
			width: 100%;
			height: 500px;
			border: none;
		}
	</style>
</head>
<body>


<!-- Anzeige der Anzahl der Versuche -->

<div>
	Versuche: <span id="counter">0</span>
	</div>


<!-- Eingabefeld und Button, um die nächste Seite zu laden -->
<!-- Button zur Abgabe, wenn man den Artikel gefunden hat -->

	<input type="text" id="input_url"><button id="url_btn">Nächste Seite →</button>
	<button id="loesung">Gefunden!</button></div>


<!-- Iframe-Element, das die Wikipedia-Seite anzeigt -->
<!-- Auslesen der Versuche; Umsetzung mit Javascript, da man den Seitentext/Links mit PHP leider nicht auslesen kann -->

	<iframe id="content" src="https://de.wikipedia.org/wiki/Spezial:Zuf%C3%A4llige_Seite"></iframe>
	<script type="text/javascript">


// Deklarierung der Funktion punktzahl()

		function punktzahl(versuche){
			console.log(versuche);


// HTML-Formular-Element erstellen

			const form = document.createElement("form");
			form.setAttribute("method", "POST");
			form.setAttribute("action", "results.php");


// Unsichtbares Input-Element erstellen

			const input = document.createElement("input");
			input.setAttribute("type", "hidden");
			input.setAttribute("name", "versuche");
			input.setAttribute("value", versuche);


// Füge das input-Element zum Formular hinzu

			form.appendChild(input);


// Füge das Formular am Ende des Body-Bereichs hinzu

			document.body.appendChild(form);


// Formular submitten

			form.submit();
		}


// Array, um die Liste der besuchten Wikipedia-Seiten zu speichern
// Click-Event-Listener auf den Gefunden-Button; Bei Klick Aufruf der Funktion punktzahl(versuche)

		var versuche = [];
		document.getElementById("loesung").addEventListener("click", ()=>{
			punktzahl(versuche);
		});


// Ausgabe des Suchworts für den Spieler

		<?php
			echo "var suchwort = '" . $_SESSION["suchwort"] . "';";
		?>


// Event-Listener, der auf das Klicken des "Nächste Seite"-Buttons reagiert

		document.getElementById("url_btn").addEventListener("click",(event)=>{
			
			// Abrufen des Inhalts des Eingabefelds
			const input = document.getElementById("input_url").value;
			console.log(input);

			// Aktualisieren des Iframe-Inhalts mit der neuen Wikipedia-Seite
			const iframe = document.getElementById("content");
			iframe.src = input;

			// Hinzufügen der neuen Wikipedia-Seite zu den Versuchen
			versuche.push(input);

			// Aktualisieren des Zählers
			const counter = document.getElementById("counter");
			counter.innerText = versuche.length;

			// Debugging-Ausgabe der Liste der Versuche
			console.log(versuche);
		});
	</script>
</body>
</html>