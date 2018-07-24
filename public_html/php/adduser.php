<?php

	function addUser($name, $surname, $email, $acctype, $indeks) {
		// wywołanie funkcji łączącej się z bazą
		$database = connectDatabase();
        $login;
		
		// zwrócenie błędu jeśli nie dostano zasobu bazy
		if ($database == false) {
			return "<p>Nie udało się połączyć z bazą danych. Spróbuj później.</p>";
		}
		
		if ($acctype == "admin") {		




            $login = $indeks;
		    @ $result = $database->query("select login 
									      from users
									      where login='$login'");

		    if ($result == false) {
			    return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		    }

		    if ($result->num_rows > 0) {
			    return "<p>Podany login jest już zarejestrowany</p>";
		    }
		    @ $result = $database->query("select min(indeks) 
									      from users");
		    if ($result == false) {
			    return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		    }
		    $row = $result->fetch_row();
		    $indeks  = $row[0] - 1;
		} else {
		@ $result = $database->query("select indeks 
									  from users
									  where indeks='$indeks'");

		if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		}

		if ($result->num_rows > 0) {
			return "<p>Numer indeksu jest już zarejestrowany.</p>";
		}
}

		@ $result = $database->query("select mail 
									  from users
									  where mail='$email'");

		if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		}

		if ($result->num_rows > 0) {
			return "<p>Adres email jest już zarejestrowany</p>";
		}


		//$password = $name . $surname;

        $zestaw_znakow = 'qwertyuiopasdfghjklzxcvbnm0123456789';
        $password = '';
        $dlugosc_zestawu = strlen($zestaw_znakow)-1;
        for ( $i = 0; $i <= 7; $i++ )
        {
            $losowy = rand(0, $dlugosc_zestawu);
            $password .= $zestaw_znakow{$losowy};
        }
        //echo $password;
        if ($acctype == "admin") {
            @ $result = $database->query("insert into users (name, surname, login, pass, mail, indeks) values ('$name', '$surname', '$login', sha1('$password'), '$email', '$indeks')");
        } else {
		    @ $result = $database->query("insert into users (name, surname, pass, mail, indeks) values ('$name', '$surname', sha1('$password'), '$email', '$indeks')");
		}
							  
		// jeśli nie udało się wykonać zapytania
		if ($result == false) {
			return "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
		} else {
            $mess1 = "Twoje konto na serwisie spoj-clone jest już aktywne. Dane do pierwszego logowania:\n\nLogin: ";
		    $mess2 = " (numer twojego indeksu)\nHasło: ";
		    $mess3 = "\n\nNie zapomnij o stworzeniu własnego loginu po pierwszym logowaniu.\n\n--------------------\n".
					    "Życzymi miłego rozwiązywania\nspoj-clone development team\n";
		    $headers = 'From: Spoj-Clone_admin@213.184.8.82'."\r\n".'Reply-To: Spoj-Clone_admin@213.184.8.82'."\r\n";
            mail($email, 'Witamy na spoj-clone [Dominatrix 2000]!!!', wordwrap($mess1.$login.$mess2.$password.$mess3, 70), $headers);
            return "<p>Użytkownik został dodany</p>";
		}
		$database->close();
	}

?>
