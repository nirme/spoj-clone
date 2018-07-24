<?php

	// sprawdzenie czy użytkownik jest zalogowany
	if (!isset($_SESSION['userIndeks'])) {
		// jeśli nie jest, to wyświetlony zostaje formularz logowania
		loginForm($category, $authStatus);
	} else {
		// jeśli jest, to wyświetlenie prawidłowej zawartości strony
		
		if ($_SESSION['isAdmin']) {
			echo "			<h2>Panel administratora</h2>\n";
		} else {
			echo "			<h2>Panel użytkownika</h2>\n";
		}
?>
			<p>MENU: <a href="?category=account&action=changepassword">Zmień hasło</a> 
<?php
		if ($_SESSION['isAdmin']) {
			// opcje dostępne dla administratora
?>
			| <a href="?category=account&action=addusers">Dodaj użytkowników</a>
			| <a href="?category=account&action=managegroups">Zarządzaj grupami</a></p>
<?php
		}
		switch ($action) {
		case 'changepassword' :
			include('php/changeform.php');
			changeForm();
			break;
		case 'addusers' :
			if ($_SESSION['isAdmin']) {
?>
			<form enctype="multipart/form-data" action="?category=uploader" method="POST">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			<p>Wybierz plik z listą uczniów:</p><br />
			<input name="uploadedfile" type="file"/>
			<input type="submit" value="Wyślij plik" />
			</form>
<?php
			}
		}
	}
?>
