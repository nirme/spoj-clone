<?php

	// plik: databasefunctions.php
	// funkcję odpowiedzialna za łączenie się z bazą danych i zwracanie zasobu

	function connectDatabase() {
		// łączenie z bazą - host, login, hasło, baza
		$database = new mysqli('localhost', 'projekt5', 'projekt5', 'projekt5');
		
		// jeżeli nie udało się połączyć z bazą danych
		if (mysqli_connect_errno()) {
			return false;
		}
		
		// zwrócenie zasobu bazy danych
		return $database;
	}

?>
