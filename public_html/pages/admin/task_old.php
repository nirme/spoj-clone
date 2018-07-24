<h3>Dodaj nowe zadanie</h3>
<p>
<?php
if ($_SESSION['isAdmin'])
{
	$database = connectDatabase();
	if ($database == false)	{	include('php/database_fail.php');	}
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
			else if ($_POST['task_mod_num'] == '0' )
			{
				if (empty($_POST['task_arg']) && empty($_POST['task_in']))
					{	$sub_title = 'Nie podałeś żadnych danych wejściowych programu';	}
				else if (empty($_POST['task_out']) && empty($_POST['task_ret']) && $_POST['task_ret'] != 0 )
					{	$sub_title = 'Nie podałeś żadnych danych wyjściowych programu';	}
				else if (!empty($_POST['task_ret']) && !is_numeric($_POST['task_ret']))
					{	$sub_title = 'Wartość return musi być liczbą';	}
			}
			else if ($_SESSION['task_num'] != $_POST['task_num'])
			{
				echo "Na tej stronie nie można odświerzać...<br/>\n";
				$ok_flag = true;
			}
			else if ($_SESSION['task'] == 1)
			{
				$ok_flag = true;
				if ($_POST['task_mod_num'] == '0' )
				{				
					$task_query1 = 'INSERT taskList (makerId, makeDate, title, description, points, runTime';
					$task_query2 = ' VALUES ('.$_SESSION['userId'].', NOW(), "'.addslashes(strip_tags(trim($_POST['task_title']))).
						'", "'.addslashes(preg_replace("/\n/", "<br/>", strip_tags(trim($_POST['task_text']), "<b><i>"))).
						'", '.(string) intval($_POST['task_points']).', '.(string) intval($_POST['task_runTime']);
					$database->query($task_query1.')'.$task_query2.');');
					$task_id = mysqli_insert_id($database);	
					
					$return_value = (string) intval($_POST['task_ret']);
					if (empty($_POST['task_ret']))
						{	$return_value = '0';	}
					$database->query('INSERT taskIO (task_id, arguments, input_string, output_string, return_value) VALUES ('.
									(string) $task_id.', "'.addslashes($_POST['task_arg']).'", "'.
									addslashes($_POST['task_in']).'", "'.
									addslashes($_POST['task_out']).'", '.$return_value.');');
					echo "Zadanie dodano do bazy danych.<br />\n";
				}
				else
				{
					$database->query('UPDATE taskList SET points='.(string) intval($_POST['task_points']).', title="'.
						addslashes(strip_tags(trim($_POST['task_title']))).'", runTime='.(string) intval($_POST['task_runTime']).
						', description="'.addslashes(preg_replace("/\n/", "<br/>", strip_tags(trim($_POST['task_text']), "<b><i>"))).
						'" WHERE id='.$_POST['task_mod_num'].' ;');
					echo "Zadanie zostało zmienione.<br />\n";
				}
				$_SESSION['task'] = 2;
				$_SESSION['task_num'] = $_SESSION['task_num']+1;
			}
		}
		else if (isset($_POST['task_mod_button']))
		{
			$result3 = $database->query("SELECT points, title, runTime, description FROM taskList  WHERE id = ".$_POST['task_id'].' ;');
			$row3 = $result3->fetch_assoc();
			$task_title = $row3['title'];
			$task_text = $row3['description'];
			$task_points = $row3['points'];
			$task_runTime = $row3['runTime'];
		}
		else if (isset($_POST['task_del_ok_but']))
		{
			$ok_flag = true;
			$database->query('DELETE FROM taskList WHERE id='.$_POST['task_id'].' ;');
			$database->query('DELETE FROM taskIO WHERE task_id='.$_POST['task_id'].' ;');
			echo "Zadanie z zestawami testowymi został usunięty\n";
		}
		else if (isset($_POST['task_del_button']))
		{
			$ok_flag = true;
			$result4 = $database->query("SELECT id, title FROM taskList  WHERE id = ".$_POST['task_id'].' ;');
			$row4 = $result4->fetch_assoc();
			echo "Czy na pewno chcesz usunąć zadanie: <br/>\n";
			echo $row4['id'].". ".$row4['title']."<br/>\ni wszystkie jego zestawy testowe?";
?>
<br/><br/>
<form action="?category=admin/task" method="POST" >
<input type="hidden" name="task_id" value="<?php	echo $_POST['task_id'];	?>" />
<center><input type="submit" value="Usuń" name="task_del_ok_but" /></center>
</form>
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
		if (isset($_POST['task_mod_button']))
			{	echo $_POST['task_id'];	}
		else
			{	echo '0';	}
?>" />
Nazwa zadania:<br/>
<textarea id="task_title" name="task_title" maxlength="255"><?php	echo $task_title;	?></textarea><br/>
Treść:<br/>
<textarea id="task_text" name="task_text"><?php	echo $task_text;	?></textarea><br/>
<table id="clear"><tr><td>
Punkty:<br/>
<input id="task_num" type="text" name="task_points"  maxlength="5" onKeyDown="javascript:return dFilter (event.keyCode, this, '#####');" 
value="<?php	echo $task_points;	?>"></input>
</td><td>
Czas w ms:<br/>
<input id="task_num" type="text" name="task_runTime" maxlength="5" onKeyDown="javascript:return dFilter (event.keyCode, this, '#####');" 
value="<?php	echo $task_runTime;	?>"></input>
</td></tr></table><br/>
<table id="clear"><tr><td>
Argumenty:<br/>
<textarea id="taskIO_arg" name="task_arg"><?php
			if (!empty($_POST['task_arg']))
				{	echo $_POST['task_arg'];	}
?></textarea><br/>
Wartość return:<br/>
<input id="taskIO_ret" type="text" name="task_ret" onKeyDown="javascript:return dFilter (event.keyCode, this, '##############');" 
value="<?php	if (!empty($_POST['task_ret']))
					{	echo $_POST['task_ret'];	}	?>"></input>
</td><td>
Dane wejściowe:<br/>
<textarea id="taskIO_IO" name="task_in"><?php
			if (!empty($_POST['task_in']))
				{	echo $_POST['task_in'];	}
?></textarea>
</td><td>
Dane wyjściowe:<br/>
<textarea id="taskIO_IO" name="task_out"><?php
			if (!empty($_POST['task_out']))
				{	echo $_POST['task_out'];	}
?></textarea>
</td></tr></table><br/><br/>
<center><input type="submit" value="Wyślij" name="task_button" /></center>
</form>

<br/>
<hr/>
<br/>
delete/edit
<?php
			$result2 = $database->query("SELECT id, title FROM taskList ORDER BY id;");
			$numberRows = $result2->num_rows;
			$tasks = "";
			$row2 = $result2->fetch_assoc();
			$tasks = $tasks."<option value=\"".$row2['id']."\" selected=\"selected\">".$row2['id'].'. '.$row2['title']."</option>\n";
			for ($i=1; $i < $numberRows; $i++)
			{
				$row2 = $result2->fetch_assoc();
				$tasks = $tasks."<option value=\"".$row2['id']."\">".$row2['id'].'. '.$row2['title']."</option>\n";
			}
?>
<form action="?category=admin/task" method="POST" >
<input type="hidden" name="task_num" value="<?php	echo $_SESSION['task_num'];	?>" />
<center><select name="task_id" id="task_edde">
<?php	echo $tasks;	?>
</select><br/><br/>
<input type="submit" value="Usuń" name="task_del_button" /> <input type="submit" value="Zmień" name="task_mod_button" /></center>
</form>
<?php
		}
		$database->close();
	}
} else {	echo "Nie masz uprawnien do przeglądania tej strony.<br />\n";	}?>
</p>



