<h1>Moje rozwiązanie</h1>
<?php
$database = connectDatabase();
if (!$database)
{	include('php/database_fail.php');	}
else
{
	if (isset($_GET['solution_id']))
	{
	$result = $database->query("SELECT solutions.*, taskList.id AS 'tskid', taskList.title AS 'tsktit', taskList.points AS 'tskpt', languages.language_name, languages.compiler_system_name FROM solutions LEFT JOIN taskList ON task_id = taskList.id LEFT JOIN languages ON lang_id = languages.id WHERE solutions.id = ".$_GET['solution_id'].';');
	$row = $result->fetch_assoc();
		if ($row['user_id'] == $_SESSION['userId'])
		{
$err_array = array(
'UNDEFINED_ERROR' => "Nieznany błąd",
'COMPILATION_ERROR' => "Błąd kompilacji",
'WAIT_FOR_RUN' => "Skompilowany",
'RUNTIME_ERROR' => "Błąd działania",
'MIXED_ERROR' => "Błąd wyników",
'NO_ERROR' => "OK",
'' => "---",
'NULL' => "---"
);
			?>
<table id="clear"><tr><td>Nr rozwiązania: &nbsp; </td><td> <?php	echo $row['id'];	?></td></tr>
<tr><td>Zadanie: &nbsp; </td><td> <?php	echo $row['tskid'].'. '.$row['tsktit'];	?></td></tr>
<tr><td>Język: &nbsp; </td><td> <?php	echo $row['language_name'].' ['.$row['compiler_system_name'].']';	?></td></tr>
<tr><td>Punktacja: &nbsp; </td><td> <?php	echo $row['points'].' / '.$row['tskpt'];	?></td></tr>
<tr><td>Data wysłania: &nbsp; </td><td> <?php	echo $row['make_date'];	?></td></tr>
<tr><td>Stan: &nbsp; </td><td> <?php	echo $err_array[$row['error']];	?></td></tr>
<tr><td>Błędy: &nbsp; </td><td> <?php	echo $row['error_str'];	?></td></tr>
<tr><td colspan="2">Kod źródłowy rozwiązania:</td></tr>
<tr><td colspan="2" style="border: 1px solid black;width: 700px;padding: 6px 6px 6px 6px;"><?php	echo $row['solution'];	?></td></tr>
</table>
<br/><hr/><br/>
<center>
<a id="topper" href="?category=user/mysolutions">&#8592; Wróć do rozwiązań</a>
<a id="topper" href="javascript:scroll(0,0)">&#8593; Do góry &#8593;</a>
</center>

			<?php

		}
	}
	$database->close();
}
?>
