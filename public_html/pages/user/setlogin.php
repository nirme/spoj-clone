<?php

	// sprawdzenie czy użytkownik jest zalogowany
	if (!isset($_SESSION['userIndeks'])) {
		// jeśli nie jest, to wyświetlony zostaje formularz logowania
		loginForm($category, $authStatus);
	} else {
        echo "<h2>Ustawienie loginu</h2>";
		if (isset($_POST['setlogin'])) {
			$newLogin = clearVariable($_POST['newlogin']);
			$repeatLogin = clearVariable($_POST['repeatlogin']);
			$changeStatus;
			
			if (empty($newLogin) || empty($repeatLogin)) {
				// zwrócenie błędu jeżeli któreś pole jest puste
				$changeStatus = "<p>Musisz wypełnić oba pola.</p>";
			} else if ($newLogin != $repeatLogin) {
				$changeStatus = "<p>Oba wpisane loginy są różne.</p>";
			} else {
				include('php/setlogin.php');
				$changeStatus = setLogin($newLogin);
			}
			
			echo $changeStatus;	
		}
		// jeśli jest, to wyświetlenie prawidłowej zawartości strony
        if ($_SESSION['userLogin']) {
            echo "<p>Posiadasz już ustawiony login.</p>";
        } else {
		// jeżeli wcisnięty został przycisk "zmień"

        echo "<p>Twoje konto nie posiada jeszcze ustalonego loginu. Logowanie poprzez numer indeksu jest niebezpieczne. Aby móc korzystać ze swojego konta należy teraz ustawić swój login, który będzie służył do logowania.</p>";

?>
			<form action="?category=user/setlogin" method="post">
			<table>
			<tr>
				<td>Login:</td>
				<td><input type="text" maxlength="16" size="16" name="newlogin" /></td>
			</tr>
			<tr>
				<td>Powtórz:</td>
				<td><input type="text" maxlength="16" size="16" name="repeatlogin" /></td>
			</tr>
            <tr colspan="2">
				<td><input id="Submit1" type="submit" name="setlogin" value="Ustaw" /></td>
            </tr>
			</table>	
			</form>
<?php
    }
    }
?>
