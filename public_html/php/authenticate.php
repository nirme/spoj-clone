<?php
	
	function authenticate($login, $password) {
		// wywołanie funkcji łączącej się z bazą
		$database = connectDatabase();
		
		// zwrócenie błędu jeśli nie dostano zasobu bazy
		if ($database == false) {
			return "Nie udało się połączyć z bazą danych. Spróbuj później.";
		}
       
        if (is_numeric($login)) {
            @ $result = $database->query("select id, name, surname, login, mail, indeks
									      from users
									      where indeks = '$login'
									      and pass=sha1('$password')");

		    // jeśli nie udało się wykonać zapytania
		    if ($result == false) {
			    return "Nie udało się wykonać zapytania. Spróbuj później.";
		    }

		    // jeśli nie otrzymano żadnego rezultatu
		    if ($result->num_rows == 0) {
			    return "Podany numer indeksu lub hasło jest błędne.";
		    }
		    $userData = $result->fetch_assoc();
            if ($userData['login']) {
                return "Dla danego numeru indeksu został utworzony login. Proszę zalogować się przy pomocy loginu.";
            }

		    $_SESSION['userId'] = $userData['id'];
		    $_SESSION['userName'] = $userData['name'];
		    $_SESSION['userSurName'] = $userData['surname'];
		    $_SESSION['userLogin'] = false;
		    $_SESSION['userMail'] = $userData['mail'];
		    $_SESSION['userIndeks'] = $userData['indeks'];
		    if ($_SESSION['userIndeks'] < 0)
			    $_SESSION['isAdmin'] = true;

        } else {
		
		    // zapytanie bazy danych o użytkownika
		    @ $result = $database->query("select id, name, surname, login, mail, indeks
									      from users
									      where login = '$login'
									      and pass=sha1('$password')");
									      
		    // jeśli nie udało się wykonać zapytania
		    if ($result == false) {
			    return "Nie udało się wykonać zapytania. Spróbuj później.";
		    }
		
		    // jeśli nie otrzymano żadnego rezultatu
		    if ($result->num_rows == 0) {
			    return "Podany login lub hasło jest błędne.";
		    }

		    // wstawienie otrzymanych danych do zmiennych sesji
		    $userData = $result->fetch_assoc();
		    $_SESSION['userId'] = $userData['id'];
		    $_SESSION['userName'] = $userData['name'];
		    $_SESSION['userSurName'] = $userData['surname'];
		    $_SESSION['userLogin'] = $login;
		    $_SESSION['userMail'] = $userData['mail'];
		    $_SESSION['userIndeks'] = $userData['indeks'];
		    if ($_SESSION['userIndeks'] < 0)
			    $_SESSION['isAdmin'] = true;
        }
			
		$database->close();
	}
	
?>
