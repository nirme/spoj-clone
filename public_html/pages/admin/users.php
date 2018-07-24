<h3>Użytkownicy</h3>
<?php
if ($_SESSION['isAdmin']) {
		if (isset($_POST['adduser'])) {
			$name = clearVariable($_POST['name']);
			$surname = clearVariable($_POST['surname']);
			$email = clearVariable($_POST['email']);
			$acctype = $_POST['acctype'];
			$indeks = clearVariable($_POST['indeks']);
			$changeStatus;
			
			if (empty($name) || empty($surname) || empty($email)) {
				// zwrócenie błędu jeżeli któreś pole jest puste
				$changeStatus = "<p>Musisz wpisać imię, nazwisko i email.</p>";
			} else if ($acctype == "user" && empty($indeks)) {
				$changeStatus = "<p>Musisz podać numer indeksu.</p>";
            } else if ($acctype == "user" && $indeks <= 0) {
				$changeStatus = "<p>Numer indeksu musi być większy od zera.</p>";
			} else if (ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $email) == false) {
				$changeStatus = "<p>Musisz wpisać poprawny adres email.</p>";
			} else {
				include('php/adduser.php');
				$changeStatus = addUser($name, $surname, $email, $acctype, $indeks);
			}
			
			echo $changeStatus;	
		}
	$database = connectDatabase();
	if (!$database)	{	include('php/database_fail.php');	}
	else
	{
		$limitSize = "50";
		$page = ' LIMIT '.$limitSize.' OFFSET 0';
		if (!empty($_GET["page"]))
		{
			$page = ' LIMIT '.$limitSize.' OFFSET '.(($_GET["page"]-1)*$limitSize);
		}

		if (!isset($_SESSION['user_num'])) {	$_SESSION['user_num'] = 1;	}
	
		if (isset($_POST['user_edit']))
		{
			$result = $database->query('SELECT name, surname, indeks, mail FROM users WHERE id = "'.$_POST['user_edit'].'" ;');
			$row = $result->fetch_assoc();
	
	?>

<center>Zmień dane użytkownika:<br/>
<table id="clear">
<form action="?category=admin/users" method="post">
<input type="hidden" name="edit" value="<?php echo $_POST['user_edit']; ?>" />
<tr><td>Imię:
<input type="text" maxlength="255" size="24" name="name" value="<?php echo $row['name']; ?>" /></td>
<td> &nbsp; &nbsp; Nazwisko:
<input type="text" maxlength="255" size="24" name="surname" value="<?php echo $row['surname']; ?>" /></td></tr><tr></tr>
<tr><td colspan="2">e-mail:
<input type="text" maxlength="255" size="40" name="mail" value="<?php echo $row['mail']; ?>" />
 &nbsp; &nbsp; Index:
<input id="ni" type="text" value="<?php echo $row['indeks']; ?>" maxlength="10" size="10" name="indeks" onKeyDown="javascript:return dFilter (event.keyCode, this, '##########');"/></td></tr>
<tr></tr><tr><td align="right"><input type="submit" value="Zmień" /></form></td>
<form action="?category=admin/users" method="post">
<td align="left"><input type="submit" value="Cofnij" /></td>
</form></tr></table></center><br/><hr/><br/>
<?php
	}
	else if (isset($_POST['user_rem']))
	{
	?>

<center>Czy na pewno chcesz usunąć użytkownika 
<?php
$result = $database->query('SELECT name, surname, indeks FROM users WHERE id = "'.$_POST['user_rem'].'" ;');
$row = $result->fetch_assoc();
echo $row['name'].' '.$row['surname'].', o indeksie '.$row['indeks'].'?';
?>
<br/><br/>
<table id="clear"><tr><td>
<form action="?category=admin/users" method="post">
<input type="hidden" name="remove" value="<?php echo $_POST['user_rem']; ?>" />
<input type="submit" value="Usuń" /></form>
</td><td>
<form action="?category=admin/users" method="post">
<input type="submit" value="Cofnij" /></form></td></tr></table>
</center>
<br/><hr/><br/>
<?php
	}
	else if (isset($_POST['edit']))
	{
		echo '<center>';
		if (empty($_POST['name']) || empty($_POST['surname']) || empty($_POST['mail']) || empty($_POST['indeks']))
			{	echo "Błąd - puste pole";	}
		else if (!is_numeric($_POST['indeks']) || strlen($_POST['name']) > 255 || strlen($_POST['surname']) > 255 || strlen($_POST['mail']) > 255)
			{	echo "Błąd - bledna wartosc/dlugosc";	}
		else
		{
			$database->query('UPDATE users SET name="'.addslashes($_POST['name']).'", surname="'.addslashes($_POST['surname']).
				'", mail="'.addslashes($_POST['mail']).'", indeks="'.$_POST['indeks'].'" WHERE id = '.$_POST['edit'].' ;');
			echo "Użytkownik został zmieniony.<br />\n";

		}
		echo '</center><br/>'."\n";
	}
	else if (isset($_POST['remove']))
	{
		$database->query('DELETE FROM users WHERE id='.$_POST['remove'].' ;');
		echo '<center>Użytkownik został usunięty.</center><br/>'."\n";
	}
	
	?>
<b>Wszyscy użytkownicy</b><br/><br/>

<table id="groups_lists" cellspacing="0" cellpadding="0">
<tr><th id="left">Indeks</th><th>Nazwisko</th><th>Imię</th><th>Login</th><th colspan="2" id="right"></th></tr>
<?php
 		$result = $database->query('SELECT id, name, surname, login, indeks FROM users ORDER BY surname '.$page.';');
		$numberRows = $result->num_rows;
		for ($i=0; $i < $numberRows; $i++)
		{
			$row = $result->fetch_assoc();
			echo '<tr><td id="num">';
			if ($row['indeks'] > 0)	{	echo $row['indeks'];	}
			else	{	echo 'admin';	}			
			echo '</td>'."\n".'<td id="surname">'.$row['surname'].'</td>'."\n".'<td id="name">'.$row['name'].
			'</td>'."\n".'<td id="login">'.$row['login'].'</td>'."\n".'<td id="edit"><form action="?category=admin/users" method="post">'.
			"\n".'<input type="hidden" name="user_edit" value="'.$row['id'].'" />'."\n".
			'<input type="submit" value="Edytuj" /></form></td>'."\n".'<td id="rem">'.
			'<form action="?category=admin/users" method="post">'."\n".'<input type="hidden" name="user_rem" value="'.
			$row['id'].'" />'."\n".'<input type="submit" value="Usuń" /></form></td></tr>'."\n\n";
		}
		echo "</table><br/>\n";

		$result = $database->query("SELECT COUNT(id) AS 'pages' FROM users;");
		$row = $result->fetch_assoc();
		$page_get = 1;
		if (!empty($_GET["page"]) && is_numeric($_GET["page"]))
			{	$page_get = $_GET["page"];	}
		$count = intval($row['pages'] / $limitSize);
		if ($row['pages'] % $limitSize)
			{	$count = $count + 1;	}
		echo '<table id="paginator"><tr>';
		if ($page_get > 1 && $page_get != "all")
			{	echo '<td><a href="index.php?category=admin/users&page='.($page_get-1).'">&#8592;</a></td>'."\n";	}
		else
			{	echo '<td><p>&#8592;</p></td>'."\n";	}
		$flag = false;
		for ($i=1; $i <= $count; $i++)
		{	
			if ($page_get != $i)
				{	echo '<td><a href="index.php?category=admin/users&page='.$i.'">'.$i.'</a></td>'."\n";	}
			else
				{	echo '<td><p><b>'.$i.'</b></p></td>'."\n";	}
		}
		if ($page_get < $count && $page_get != "all")
			{	echo '<td><a href="index.php?category=admin/users&page='.($page_get+1).'">&#8594;</a></td>';	}
		else
			{	echo '<td><p>&#8594;</p></td>';	}
		echo "\n</table>\n";
?>
<br/><hr/><br/>
<?php

?>
<b>Dodaj użytkownika</b>
<form action="?category=admin/users" method="post">
<table>
<tr>
<td>Imię:</td>
<td><input type="text" maxlength="16" size="16" name="name" value="<?php echo $name; ?>" /></td>
</tr>
<tr>
<td>Nazwisko:</td>
<td><input type="text" maxlength="16" size="16" name="surname" value="<?php echo $surname; ?>" /></td>
</tr>
<tr>
<td>Adres e-mail:</td>
<td><input type="text" maxlength="64" size="16" name="email" value="<?php echo $email; ?>" /></td>
</tr>
<tr>
<td>Typ konta:</td>
<td>
<input id="user" type="radio" name="acctype" value="user" checked="checked" onclick="javascript:accountType('u')"> User </input>
<input id="admin" type="radio" name="acctype" value="admin" onclick="javascript:accountType('a')"> Admin </input>
</td>
</tr>
<tr>
<td id="title">
<script type="text/javascript">	document.write("Numer indeksu: ")	</script>
<noscript>Index / Login:</noscript>
</td>
<td><input id="ni" type="text" maxlength="10" size="16" name="indeks" onKeyDown="javascript:return dFilter (event.keyCode, this, '##########');"/></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" name="adduser" value="Dodaj" /></td></tr>
</table>	
</form>
<br/><hr/><br/>
<b>Dodaj użytkowników</b><br/>
<?php $_SESSION['users_add'] = 1; ?>
<p>Wyślij plik exela (.xls) by natychmiast utworzyć większe ilości kont.<br />
Tabela w pliku powinna mieć następujący format:<br />
<center>| Imię | Nazwisko | nr_indexu | email |</center><br />
Patmiętaj by plik został zapisany w standardowym formacie o rozszerzeniu .xls, oraz by jego rozmiar nie przekraczał 400kB.</p>
<br/>
<form enctype="multipart/form-data" action="?category=admin/users_uploader" method="POST" >
<input type="hidden" name="MAX_FILE_SIZE" value="524288" />
<center>
<label for="file">Plik:</label>
<input type="file" name="uploadedfile" accept="
application/vnd.ms-excel,
application/msexcel,
application/x-msexcel,
application/x-ms-excel,
application/vnd.ms-excel,
application/x-excel,
application/x-dos_ms_excel,
application/xls"/><br/><br/>

Możesz automatycznie dodać wszystkich użytkowników do grupy:<br/><br/>
<select id="ch_group" name="group_ex">
<option value="0">Wybierz grupę</option>
<?php
		$result = $database->query("SELECT id, name FROM groups ORDER BY name;");
		$numberRows = $result->num_rows;
		for ($i=0; $i < $numberRows; $i++)
		{
			$row = $result->fetch_assoc();
			echo '<option value="'.$row['id'].'" >'.$row['name']."</option>\n";
		}


?></select>
 lub stwórz nową 
<input type="text" maxlength="255" name="group_new"/>
<br/><br/>
<input type="submit"value="Wyślij plik" />
</center>
</form>
<br/><hr/><br/><center>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
	}
} else {
?>
<p>Nie masz uprawnien do przeglądania tej strony</p>
<?php } ?>
