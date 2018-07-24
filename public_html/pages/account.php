<?php

	// sprawdzenie czy użytkownik jest zalogowany
	if (!isset($_SESSION['userIndeks'])) {
		// jeśli nie jest, to wyświetlony zostaje formularz logowania
		loginForm($category, $authStatus);
	} else {
		// jeśli jest, to wyświetlenie prawidłowej zawartości strony
?>
	<h2>Moje konto</h2>
	<h3>Dane użytkownika:</h3>
	<table id="clear" cellspacing="5">
	<tr>
		<td><b>Imię:</b></td>
		<td><?php echo $_SESSION['userName']; ?></td>
	</tr>
	<tr>
		<td><b>Nazwisko:</b></td>
		<td><?php echo $_SESSION['userSurName']; ?></td>
	</tr>
		<td><b>E-mail:</b></td>
		<td><?php echo $_SESSION['userMail']; ?></td>
	</tr>
	</tr>
		<td colspan="2" align="center"><a href="?category=user/changepassword">Zmień hasło</a></td>
	</tr>
	</table>
<?php
if (!isset($_SESSION['isAdmin'])) {
    $userId = $_SESSION['userId'];
	$database = connectDatabase();
		if ($database == false) {
			echo "<p>Nie udało się połączyć z bazą danych. Spróbuj później.</p>";
		}

?>
<hr />
<hr />
	<h3>Dane zadań:</h3>
<table id="clear" cellspacing="5">
<?php 
@ $result = $database->query("select points from users where id = '$userId'");

$row = $result->fetch_assoc();
?>
	<tr>
		<td><b>Punkty:</b></td>
		<td><?php echo $row['points']; ?></td>
	</tr>
<?php
$groups_number = 1;
@ $result = $database->query("select groups.name from groups, user_to_group where user_to_group.user_id = '$userId' and groups.id = user_to_group.group_id");
if ($result->num_rows) {
$groups_number = $result->num_rows;

}
?>
	<tr>
		<td><b>Grupy:</b></td>
		<td><?php for($i = 0; $i < $groups_number; $i++) { $row = $result->fetch_array(); echo $row[0]; if($i + 1 != $groups_number) echo "<br />"; } ?></td>
	</tr>

	</table>
	<p><b>Przydzielone zadania:</b></p>

    <table id="task_list">
	<tr><th>ID</th><th>nazwa zadania</th><th>punkty</th><th>data</th></tr>
	<?php
$groups_number = 1;
@ $result = $database->query("select group_id from user_to_group where user_to_group.user_id = '$userId'");
if ($result->num_rows) {
$groups_number = $result->num_rows;
}

    $query = "SELECT DISTINCT id, title, points, makeDate FROM taskList, task_to_group WHERE taskList.id = task_to_group.task_id and (";


    for($i = 0; $i < $groups_number; $i++){
        $row = $result->fetch_array();
        if ($i)
            $query .= " or ";

        $query .= "group_id = " . $row[0];
    }
    $query .= ")";
	@ $result = $database->query($query);
	if ($result == false) {
	    echo "<p>Nie udało się wykonać zapytania. Spróbuj później.</p>";
    }
	$numberRows = $result->num_rows;
	for ($i=0; $i < $numberRows; $i++)
	{
	$row = $result->fetch_assoc();
	echo 
		'<tr><td id="task_id">'.$row['id'].'</td><td id="task_title">'.
		'<a href="index.php?category=task_info&task_id='.$row['id'].'&page='.$_GET['page'].'">'.$row['title'].'</a>'.
		'</td><td id="task_points">'.$row['points'].
		'</td><td id="task_date">'.$row['makeDate'].'</td></tr>'."\n";
	}
	echo "</table><br/>\n";
		$database->close();
    }

	}
?>
