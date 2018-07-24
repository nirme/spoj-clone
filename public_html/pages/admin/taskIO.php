<h3>Dodaj zestaw testowy do zadania</h3>

<p>
<?php
if ($_SESSION['isAdmin'])
{
	$database = connectDatabase();
	if (!$database)	{	include('php/database_fail.php');	}
	else
	{
		if (!isset($_SESSION['taskIO_num']))
			{	$_SESSION['taskIO_num']=1;	}
		$ok_flag = false;
		$sub_title = "";
		$task_id = "";
		$task_arg = "";
		$task_ret = "";
		$task_in = "";
		$task_out = "";
		if (isset($_POST['taskIO_button']))
		{
			$task_id = $_POST['task_id'];
			$task_arg = $_POST['task_arg'];
			$task_ret = $_POST['task_ret'];
			$task_in = $_POST['task_in'];
			$task_out = $_POST['task_out'];
			
			if (!empty($_POST['task_ret']) && !is_numeric($_POST['task_ret']))
				{	$sub_title = 'Wartość return musi być liczbą';	}
			else if (empty($_POST['task_arg']) && empty($_POST['task_in']))
				{	$sub_title = 'Nie podałeś żadnych danych wejściowych programu';	}
			else if (empty($_POST['task_ret']) && empty($_POST['task_out']) && $_POST['task_ret'] != 0 )
				{	$sub_title = 'Nie podałeś żadnych danych wyjściowych programu';	}
			else if ($_SESSION['taskIO_num'] != $_POST['taskIO_num'])
			{
				echo "Na tej stronie nie można odświerzać...<br/>\n";
				$ok_flag = true;
			}
			else if ($_SESSION['taskIO'] == 1)
			{
				$ok_flag = true;
				echo "<br/><center>";
				if ($_POST['taskIO_mod_num'] == '0' )
				{				
					$return_value = $_POST['task_ret'];
					if (empty($_POST['task_ret']))
						{	$return_value = '0';	}
					$database->query('INSERT taskIO (task_id, arguments, input_string, output_string, return_value) VALUES ('.
									$_POST['task_id'].', "'.
									addslashes($_POST['task_arg']).'", "'.
									addslashes($_POST['task_in']).'", "'.
									addslashes($_POST['task_out']).'", '.
									$return_value.');');
					echo "Zestaw dodano do bazy danych.<br />\n";
				}
				else
				{
					$return_value = $_POST['task_ret'];
					if (empty($_POST['task_ret']))
						{	$return_value = '0';	}
					$database->query('UPDATE taskIO SET task_id='.$_POST['task_id'].
									', arguments="'.addslashes($_POST['task_arg']).
									'", input_string="'.addslashes($_POST['task_in']).
									'", output_string="'.addslashes($_POST['task_out']).
									'", return_value='.$return_value.
									' WHERE id='.$_POST['taskIO_mod_num'].' ;');
					echo "Zestaw został zmieniony.<br />\n";
				}
				echo "<br/><br/>\n";
				echo '<a id="topper" href="index.php?category=admin/taskIO">&#8592; Wróć</a>';
				echo '<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>'."\n";
				echo "</center>\n";
				$_SESSION['taskIO'] = 2;
				$_SESSION['taskIO_num'] = $_SESSION['taskIO_num']+1;
			}
		}
		else if (isset($_POST['taskIO_del_button']))
		{
			$ok_flag = true;
			$result4 = $database->query("SELECT id, task_id FROM taskIO  WHERE id = ".$_POST['taskIO_mod_num'].' ;');
			$row4 = $result4->fetch_assoc();
			echo "Czy na pewno chcesz usunąć zestaw testowy <br/>\n";
			echo 'nr '.$row4['id'].' zadania '.$row4['task_id']."<br/>\n?";
?>
<br/><br/>
<center><table id="clear"><tr>
<td><form action="?category=admin/taskIO" method="POST" >
<input type="submit" value="Cofnij" name="taskIO_del_err_but" />
</form></td>
<td><form action="?category=admin/taskIO" method="POST" >
<input type="hidden" name="taskIO_mod_num" value="<?php	echo $_POST['taskIO_mod_num'];	?>" />
<center><input type="submit" value="Usuń" name="taskIO_del_ok_but" /></center>
</form></td></tr></table><br/>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		}
		else if (isset($_POST['taskIO_mod_button']))
		{
			$result3 = $database->query("SELECT task_id, arguments, input_string, output_string, return_value FROM taskIO WHERE id = ".$_POST['taskIO_mod_num'].' ORDER BY task_id, id ;');
			$row3 = $result3->fetch_assoc();
			$task_id = $row3['task_id'];
			$task_arg = $row3['arguments'];
			$task_ret = $row3['return_value'];
			$task_in = $row3['input_string'];
			$task_out = $row3['output_string'];
		}
		else if (isset($_POST['taskIO_del_ok_but']))
		{
			$ok_flag = true;
			$database->query('DELETE FROM taskIO WHERE id='.$_POST['taskIO_mod_num'].' ;');
			echo "<br/><center>";
			echo "Zestaw testowy został usunięty\n";
			echo "<br/><br/>\n";
			echo '<a id="topper" href="index.php?category=admin/news">&#8592; Wróć</a>';
			echo '<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>'."\n";
			echo "</center>\n";

		}
		if (!$ok_flag)
		{
			$result = $database->query("SELECT id, title FROM taskList ORDER BY id;");
			$numberRows = $result->num_rows;
			$tasks = "";
			$i=0;
			if (empty($task_id))
			{
				$row = $result->fetch_assoc();
				$tasks = $tasks."<option value=\"".$row['id']."\" selected=\"selected\">".$row['id'].'. '.$row['title']."</option>\n";
				$i++;
			}
			for ($i; $i < $numberRows; $i++)
			{
				$row = $result->fetch_assoc();
				if ( $task_id == $row['id'] )
					{	$tasks = $tasks."<option value=\"".$row['id']."\" selected=\"selected\">".$row['id'].'. '.$row['title']."</option>\n";	}
				else
					{	$tasks = $tasks."<option value=\"".$row['id']."\">".$row['id'].'. '.$row['title']."</option>\n";	}
			}
			$_SESSION['taskIO'] = 1;
			echo "<br/>\n".$sub_title;
			if (!empty($sub_title))	{	echo ":<br />\n";	}	?>
<form action="?category=admin/taskIO" method="POST" >
<input type="hidden" name="taskIO_num" value="<?php	echo $_SESSION['taskIO_num'];	?>" />
<input type="hidden" name="taskIO_mod_num" value="<?php
			if (isset($_POST['taskIO_mod_num']))
				{	echo $_POST['taskIO_mod_num'];	}
			else
				{	echo '0';	}
?>" />
Zadanie: 
<select name="task_id">
<?php	echo $tasks;	?>
</select><br/><br/>
<table id="clear"><tr><td>
Argumenty:<br/>
<textarea id="taskIO_arg" name="task_arg"><?php	echo $task_arg;	?></textarea><br/>
Wartość return:<br/>
<input id="taskIO_ret" type="text" name="task_ret" onKeyDown="javascript:return dFilter (event.keyCode, this, '##############');" 
value="<?php	echo $task_ret; ?>"></input>
</td><td>
Dane wejściowe:<br/>
<textarea id="taskIO_IO" name="task_in"><?php	echo $task_in; ?></textarea>
</td><td>
Dane wyjściowe:<br/>
<textarea id="taskIO_IO" name="task_out"><?php	echo $task_out; ?></textarea>
</td></tr></table><br/>
<center><input type="submit" value="Wyślij" name="taskIO_button" /></center>
</form>

<br/>
<hr/>
<br/>
delete/edit
<?php
			$result2 = $database->query("SELECT id, task_id FROM taskIO ORDER BY task_id, id;");
			$numberRows = $result2->num_rows;
			$tasks = "";
			$task_counter=1;
			$row2 = $result2->fetch_assoc();
			$task_flag=$row2['task_id'];
			$tasks = $tasks."<option value=\"".$row2['id']."\" selected=\"selected\">Zadanie ".$row2['task_id'].'. - zestaw '.$task_counter.".</option>\n";
			for ($i=1; $i < $numberRows; $i++)
			{
				$row2 = $result2->fetch_assoc();
				if ($task_flag==$row2['task_id'])
					{	$task_counter++;	}
				else
				{
					$task_counter=1;
					$task_flag=$row2['task_id'];
				}
				$tasks = $tasks."<option value=\"".$row2['id']."\">Zadanie ".$row2['task_id'].'. - zestaw '.$task_counter.".</option>\n";

			}
?>
<form action="?category=admin/taskIO" method="POST" >
<input type="hidden" name="taskIO_num" value="<?php	echo $_SESSION['taskIO_num'];	?>" />
<center><select name="taskIO_mod_num" id="taskIO_edde">
<?php	echo $tasks;	?>
</select><br/><br/>
<input type="submit" value="Usuń" name="taskIO_del_button" /> <input type="submit" value="Zmień" name="taskIO_mod_button" /></center>
</form><br/><hr/><br/><center>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		}
		$database->close();
	}
} else {	echo "Nie masz uprawnien do przeglądania tej strony.<br />\n";	}?>
</p>
