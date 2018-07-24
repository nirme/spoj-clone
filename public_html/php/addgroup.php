<?php

	function addGroup($group) {
		// wywołanie funkcji łączącej się z bazą
		$database = connectDatabase();
		
		// zwrócenie błędu jeśli nie dostano zasobu bazy
		if ($database == false) {
			return "<p>Nie udało się połączyć z bazą danych. Spróbuj później.</p>";
		}

		@ $result = $database->query("select name 
									  from groups
									  where name='$group'");

		if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		}

		if ($result->num_rows > 0) {
			return "<p>Grupa o takiej nazwie już istnieje.</p>";
		}

	   	// zapytanie bazy danych o użytkownika
		@ $result = $database->query("insert into groups (name) values ('$group')");
									  
		// jeśli nie udało się wykonać zapytania
		if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		} else {
			return "<p>Grupa została dodana</p>";
		}
		$database->close();
	}

?>
