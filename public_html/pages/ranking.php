<h1>Ranking</h1>
<?php
	// wywołanie funkcji łączącej się z bazą
	$database = connectDatabase();

	// zwrócenie błędu jeśli nie dostano zasobu bazy
if ($database == false) {
include('php/database_fail.php');
	}
else
{

?>
<table cellpadding="0" cellspacing="2" id="groups_lists">
<tr>
<th id="left">Lp.</th>
<th>Login</th>
<?php
if($_SESSION['isAdmin']) { ?>
<th>Nr Indeksu</th>
<th>Imię</th>
<th>Nazwisko</th>
<?php } ?>
<th>Wynik</th>
</tr>
<?php
// zapytanie bazy danych o użytkownika
@ $result = $database->query("select name, surname, login, indeks, points
from users
where indeks > 0
order by points desc");


	$numberRows = $result->num_rows;

for ($i = 0; $i < $numberRows; $i++) {
$row = $result->fetch_assoc();

echo "<tr>";
echo "<td id=\"left\">" . ($i + 1) . "</td>";
echo "<td id=\"txt\">" . $row['login'] . "</td>";
if($_SESSION['isAdmin']) {
echo "<td id=\"num\">" . $row['indeks'] . "</td>";
echo "<td id=\"txt\">" . $row['name'] . "</td>";
echo "<td id=\"txt\">" . $row['surname'] . "</td>";
}
echo "<td id=\"num\">" . $row['points'] . "</td>";
echo "</tr>";

}

$database->close();
}
?>
</table>
