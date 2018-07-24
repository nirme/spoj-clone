<h3>Dodaj nowe zadanie</h3>
<p>
<?php
if ($_SESSION['isAdmin'])
{
	$database = connectDatabase();
	if (!$database)	{	include('php/database_fail.php');	}
	else
	{
		if (!isset($_SESSION['task_num']))
			{	$_SESSION['task_num']=1;	}
		$ok_flag = false;
		$sub_title = '';
		$task_title = "";
		$task_text = "";
		$task_points = "";
		$task_runTime = "";
		if (isset($_POST['task_button']))
		{
			$task_num = '0';
			$task_title = $_POST['task_title'];
			$task_text = $_POST['task_text'];
			$task_points = $_POST['task_points'];
			$task_runTime = $_POST['task_runTime'];
			if (empty($_POST['task_title']))
				{	$sub_title = 'Nie podałeś nazwy zadania';	}
			else if (empty($_POST['task_text']))
				{	$sub_title = 'Nie podałeś treści zadania';	}
			else if (empty($_POST['task_points']) || !is_numeric($_POST['task_points']))
				{	$sub_title = 'Ilość punktów musi być liczbą';	}
			else if (empty($_POST['task_runTime']) || !is_numeric($_POST['task_runTime']))
				{	$sub_title = 'Czas na zadanie musi być liczbą';	}
			else if ($_SESSION['task_num'] != $_POST['task_num'])
			{
				echo "Na tej stronie nie można odświerzać...<br/>\n";
				$ok_flag = true;
			}
			else if ($_SESSION['task'] == 1)
			{
				echo "<br/><center>";
				$ok_flag = true;
				if ($_POST['task_mod_num'] == '0' )
				{				
					$task_query1 = 'INSERT taskList (makerId, makeDate, title, description, points, runTime';
					$task_query2 = ' VALUES ('.$_SESSION['userId'].', NOW(), "'.addslashes(strip_tags(trim($_POST['task_title']))).
						'", "'.addslashes(preg_replace("/\n/", "<br/>", trim($_POST['task_text']))).
						'", '.(string) intval($_POST['task_points']).', '.(string) intval($_POST['task_runTime'] * 1000);
					$database->query($task_query1.')'.$task_query2.');');
					echo "Zadanie dodano do bazy danych.<br />\n";
				}
				else
				{
					$database->query('UPDATE taskList SET points='.(string) intval($_POST['task_points']).', title="'.
						addslashes(strip_tags(trim($_POST['task_title']))).'", runTime='.(string) intval($_POST['task_runTime'] * 1000).
						', description="'.addslashes(preg_replace("/\n/", "<br/>", trim($_POST['task_text']))).
						'" WHERE id='.$_POST['task_mod_num'].' ;');
					echo "Zadanie zostało zmienione.<br />\n";
				}
				echo "<br/><br/>\n";
				echo '<a id="topper" href="index.php?category=admin/task">&#8592; Wróć</a>';
				echo '<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>'."\n";
				echo "</center>\n";
				$_SESSION['task'] = 2;
				$_SESSION['task_num'] = $_SESSION['task_num']+1;
			}
		}
		else if (isset($_POST['task_mod_button']))
		{
			$result3 = $database->query("SELECT points, title, runTime, description FROM taskList  WHERE id = ".$_POST['task_mod_num'].' ;');
			$row3 = $result3->fetch_assoc();
			$task_num = $_POST['task_mod_num'];
			$task_title = $row3['title'];
			$task_text = preg_replace("/<br\/>/", "\n", $row3['description']);
			$task_points = $row3['points'];
			$task_runTime = $row3['runTime'] / 1000;
		}
		else if (isset($_POST['task_del_ok_but']))
		{
			echo "<br/><center>";
			$ok_flag = true;
			$database->query('DELETE FROM taskList WHERE id='.$_POST['task_mod_num'].' ;');
			echo "Zadanie z zestawami testowymi został usunięty\n";
			echo "<br/><br/>\n";
			echo '<a id="topper" href="index.php?category=admin/task">&#8592; Wróć</a>';
			echo '<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>'."\n";
			echo "</center>\n";

		}
		else if (isset($_POST['task_del_button']))
		{
			$ok_flag = true;
			$result4 = $database->query("SELECT id, title FROM taskList  WHERE id = ".$_POST['task_mod_num'].' ;');
			$row4 = $result4->fetch_assoc();
			echo "Czy na pewno chcesz usunąć zadanie: <br/>\n";
			echo $row4['id'].". ".$row4['title']."<br/>\ni wszystkie jego zestawy testowe?";
?>
<br/><br/>
<center><table id="clear"><tr>
<td><form action="?category=admin/task" method="POST" >
<input type="submit" value="Cofnij" name="task_del_err_but" />
</form></td>
<td><form action="?category=admin/task" method="POST" >
<input type="hidden" name="task_mod_num" value="<?php	echo $_POST['task_mod_num'];	?>" />
<center><input type="submit" value="Usuń" name="task_del_ok_but" /></center>
</form></td></tr></table><br/>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		}
		if (!$ok_flag)
		{
			$_SESSION['task'] = 1;
			echo $sub_title;
			if (!empty($sub_title))	
				{	echo ":\n";	}
			else
				{	echo "<br />\n";	}	?>
<form id="task_add" action="?category=admin/task" method="POST" >
<input type="hidden" name="task_num" value="<?php	echo $_SESSION['task_num'];	?>" />
<input type="hidden" name="task_mod_num" value="<?php
		if (isset($_POST['task_mod_num']))
			{	echo $_POST['task_mod_num'];	}
		else
			{	echo '0';	}
?>" />
Nazwa zadania:<br/>
<textarea id="task_title" name="task_title" maxlength="255"><?php	echo $task_title;	?></textarea><br/>
Treść:<br/>
<textarea id="task_text" name="task_text"><?php	echo $task_text;	?></textarea><br/>
<center>
<table id="clear"><tr><td>
Punkty: 
<input id="task_num" type="text" name="task_points"  maxlength="4" onKeyDown="javascript:return dFilter (event.keyCode, this, '####');" 
value="<?php	echo $task_points;	?>"></input>
</td><td>
Czas w s: 
<input id="task_num" type="text" name="task_runTime" maxlength="5" onKeyDown="javascript:return dFilter (event.keyCode, this, '#####');" 
value="<?php	echo $task_runTime;	?>"></input>
</td></tr></table><br/>
<input type="submit" value="Wyślij" name="task_button" /></center>
</form>

<br/>
<hr/>
<br/>
delete/edit
<?php
			$result2 = $database->query("SELECT id, title FROM taskList ORDER BY id;");
			$numberRows = $result2->num_rows;
			$tasks = "";
			for ($i=0; $i < $numberRows; $i++)
			{
				$row2 = $result2->fetch_assoc();
				if ($task_num ==$row2['id'])
					{	$tasks = $tasks."<option value=\"".$row2['id']."\" selected=\"selected\">".$row2['id'].'. '.$row2['title']."</option>\n";	}
				else
					{	$tasks = $tasks."<option value=\"".$row2['id']."\">".$row2['id'].'. '.$row2['title']."</option>\n";	}
			}
?>
<form action="?category=admin/task" method="POST" >
<input type="hidden" name="task_num" value="<?php	echo $_SESSION['task_num'];	?>" />
<center><select name="task_mod_num" id="task_edde">
<?php	echo $tasks;	?>
</select><br/><br/>
<input type="submit" value="Usuń" name="task_del_button" /> <input type="submit" value="Zmień" name="task_mod_button" /></center>
</form><br/><hr/><br/><center>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		}
		$database->close();
	}
} else {	echo "Nie masz uprawnien do przeglądania tej strony.<br />\n";	}?>
</p>



