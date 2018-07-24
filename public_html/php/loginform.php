<?php

	// plik: loginform.php
	// funkcja wyświetla formularz logowania oraz ewentualne komunikaty o
	// błędach napotkanych podczas logowania

	function loginForm($category, $authStatus) {
		echo "		  <h3>Brak dostępu</h3>\n";
		echo "		  <p>Aby uzyskać dostęp do zawartości serwisu musisz być zalogowany:</p>\n";
		if (!empty($authStatus)) {
			echo "<p class=\"error\">" . $authStatus . "</p>\n";
		}
?>
		<form action="index.php?category=account" method="post">		   
			<table id="clear"><tr><td>Login:</td><td> <input id="Text1" type="text" maxlength="16" name="login" /></td></tr>
			<tr><td>Hasło:</td><td> <input id="Password1" type="password" maxlength="16" name="password" /></td></tr>
			<tr><td colspan="2" align="center"><input id="Submit1" type="submit" name="authenticate" value="Zaloguj" /></td></table>
		</form>	
<?php
	}
	
?>
