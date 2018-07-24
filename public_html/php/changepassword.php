<?php

	function changePassword($oldPassword, $newPassword) {
		// wywołanie funkcji łączącej się z bazą
		$database = connectDatabase();
		$email = $_SESSION['userMail'];
		
		// zwrócenie błędu jeśli nie dostano zasobu bazy
		if ($database == false) {
			return "<p>Nie udało się połączyć z bazą danych. Spróbuj później.</p>";
		}
		
		@ $result = $database->query("select mail 
									  from users
									  where pass = sha1('$oldPassword')");
									  
		// jeśli nie udało się wykonać zapytania
		if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		}
		
		// jeśli nie otrzymano żadnego rezultatu
		if ($result->num_rows == 0) {
			return "<p>Podane hasło jest błędne.</p>";
		}							 
		
		// zapytanie bazy danych o użytkownika
		@ $result = $database->query("update users
									  set pass = sha1('$newPassword')
									  where mail = '$email'
									  and pass=sha1('$oldPassword')");
									  
		// jeśli nie udało się wykonać zapytania
		if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		} else {
			return "<p>Hasło zostało poprawnie zmienione.</p>";
		}

		$database->close();
	}

?>
