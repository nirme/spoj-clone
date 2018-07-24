<?php

	function changeForm() {
	
		// jeżeli wcisnięty został przycisk "zmień"
		if (isset($_POST['changepassword'])) {
			$newPassword = clearVariable($_POST['newpassword']);
			$repeatPassword = clearVariable($_POST['repeatpassword']);
			$oldPassword = clearVariable($_POST['oldpassword']);
			$changeStatus;
			
			if (empty($newPassword) || empty($repeatPassword) || empty($oldPassword)) {
				// zwrócenie błędu jeżeli któreś pole jest puste
				$changeStatus = "<p>Musisz wpisać login i hasło.</p>";
			} else if ($newPassword != $repeatPassword) {
				$changeStatus = "<p>Nowe hasło i powtórzone hasło są różne.</p>";
			} else {
				include('php/changepassword.php');
				$changeStatus = changePassword($oldPassword, $newPassword);
			}
			
			echo $changeStatus;	
		}
?>
			<form action="?category=account&action=changepassword" method="post">
			<table>
			<tr>
				<td>Nowe hasło:</td>
				<td><input id="Password1" type="password" maxlength="16" size="16" name="newpassword" /></td>
			</tr>
			<tr>
				<td>Powtórz:</td>
				<td><input id="Password1" type="password" maxlength="16" size="16" name="repeatpassword" /></td>
			</tr>
			<tr>
				<td>Stare hasło:</td>
				<td><input id="Password1" type="password" maxlength="16" size="16" name="oldpassword" /></td>
			</tr colspan="2">
				<td><input id="Submit1" type="submit" name="changepassword" value="Zmień" /></td>
			</table>	
			</form>
<?php
	}

?>
