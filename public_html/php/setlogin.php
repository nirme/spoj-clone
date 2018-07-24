<?php

	function setLogin($login) {
        $database = connectDatabase();
        $indeks = $_SESSION['userIndeks'];
                
        // zwrócenie błędu jeśli nie dostano zasobu bazy
		if ($database == false) {
			return "<p>Nie udało się połączyć z bazą danych. Spróbuj później.</p>";
		}

		@ $result = $database->query("select * 
									  from users
									  where login = '$login'");
		// jeśli nie udało się wykonać zapytania
		if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		}
		
		// jeśli nie otrzymano żadnego rezultatu
		if ($result->num_rows > 0) {
			return "<p>Podany login jest już zarejestrowany. Musisz wybrać inny.</p>";
		}		

        @ $result = $database->query("update users
									  set login = '$login'
									  where indeks = '$indeks'");
		// jeśli nie udało się wykonać zapytania
		if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		} else {
            $_SESSION['userLogin'] = $login;
			return "<p>Login został ustawiony poprawnie.</p>";

		}

    }
?>
