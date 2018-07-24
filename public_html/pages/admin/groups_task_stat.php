<h1>Zadanie grupy</h1>
<?php
if ($_SESSION['isAdmin'])
{
	$database = connectDatabase();
	if (!$database)	{	include('php/database_fail.php');	}
	else
	{
		$result = $database->query("SELECT * FROM task_to_group WHERE task_id = ".$_GET['task_id']." AND group_id = ".$_GET['group_id'].";");
		$numberRows = $result->num_rows;
		if (isset($_GET['task_id']) && isset($_GET['group_id']) && $numberRows == 1)
		{
$err_array = array (
'UNDEFINED_ERROR' => "Nieznany błąd",
'COMPILATION_ERROR' => "Błąd kompilacji",
'WAIT_FOR_RUN' => "Skompilowany",
'RUNTIME_ERROR' => "Błąd działania",
'MIXED_ERROR' => "Błąd wyników",
'NO_ERROR' => "OK",
'' => "---",
'NULL' => "---"
);

			$result = $database->query("SELECT CONCAT(id,'. ',title) AS 'title', points FROM taskList WHERE id = ".$_GET['task_id'].";");
			$row = $result->fetch_assoc();
			$points = $row['points'];
			$tsktit = $row['title'];
			$result = $database->query("SELECT name FROM groups WHERE id = ".$_GET['group_id'].";");
			$row = $result->fetch_assoc();
			$group = $row['name'];
			echo '<b>Rozwiązania zadania '.$tsktit.' w grupie '.$group.'</b><br/><br/>';
			$result = $database->query("SELECT DISTINCT * FROM (SELECT users.name, users.surname, users.indeks,  CONCAT(solutions.points,'/',".$points.") AS 'points', error, CONCAT(languages.language_name,' [',languages.compiler_system_name,']') AS 'lang' FROM user_to_group LEFT JOIN users ON user_to_group.user_id = users.id LEFT JOIN solutions ON users.id = solutions.user_id LEFT JOIN languages ON solutions.lang_id = languages.id WHERE user_to_group.group_id = ".$_GET['group_id']." AND ( solutions.task_id = ".$_GET['task_id']." OR solutions.task_id IS NULL ) ORDER BY users.surname ASC, solutions.make_date DESC) AS tab GROUP BY tab.indeks;");
			$numberRows = $result->num_rows;
			?>
<center><table cellpadding="0" cellspacing="0" id="grtsus_lists">
<tr><th id="left">Indeks</th><th>Nazwisko</th><th>Imię</th><th>Stan</th><th>Punkty</th><th id="right">Język</th></tr>

<?php
			for ($i=0; $i < $numberRows; $i++)
			{
				$row = $result->fetch_assoc();
				echo '<tr><td id="ind">'.$row['indeks'].'</td>'."\n".'<td id="nam">'.$row['surname'].'</td>'."\n".
					'<td id="nam">'.$row['name'].'</td>'."\n".'<td id="err">'.$err_array[$row['error']].'</td>'."\n".
					'<td id="poi">'.$row['points'].'</td>'."\n".'<td id="lng">'.$row['lang'].'</td></tr>'."\n";
			}
			?>
</table></center>
<?php
				
		}
		else if(isset($_GET['group_id']) && !isset($_GET['task_id']))
		{
		?>
<br/><br/><center><b>Wybierz zadanie, które chcesz przejrzeć.</b><br/>
<form id="ch_group" action="index.php" method="get">
<input type="hidden" name="category" value="admin/groups_add_tasks" /><br/>
<input type="hidden" name="group_id" value="$_GET['group_id']" /><br/>
<select id="ch_group" name="task_id">
<?php
			$result = $database->query("SELECT taskList.id, taskList.title FROM taskList LEFT JOIN task_to_group ON id = task_id WHERE group_id = ".$_GET['group_id']." ORDER BY id;");
			$numberRows = $result->num_rows;
			for ($i=0; $i < $numberRows; $i++)
			{
				$row = $result->fetch_assoc();
				echo '<option value="'.$row['id'].'" >'.$row['id'].'. '.$row['name']."</option>\n";
			}
?></select>
<input type="submit" value="Wybierz"/></form></center><br/><br/><br/>
<?php
		}	
	}?>
<br/><hr/><br/>
<center>
<a id="topper" href="?category=admin/group_edit&group_id=<?php	echo $_GET['group_id'];	?>">&#8592; Do info grupy </a>
<a id="topper" href="?category=admin/groups_wt">&#8592; Do wyboru grupy </a>
<a id="topper" href="?category=admin/adminPanel">&#8593; Wroć do panelu &#8593;</a>
</center>
<?php
	$database->close();
}
?>
