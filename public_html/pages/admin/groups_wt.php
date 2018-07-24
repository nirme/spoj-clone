<h3>Grupy</h3>
<p>
<?php
if ($_SESSION['isAdmin'])
{

	$database = connectDatabase();
	if (!$database)	{	include('php/database_fail.php');	}
	else
	{
		$group_txt = "Nazwa nowej grupy:";
		$gr_name="";
		$mod_id=0;
		$send_txt = 'Dodaj';

		$groups_tit2 = 'Lub dodaj nową grupę:';

		if (!isset($_SESSION['group_num'])) {	$_SESSION['group_num'] = 1;	}

		if (isset($_POST['group_butt']))
		{
			if (empty($_POST['group_name']))
				{	echo "Nie wpisałeś nazwy grupy.<br/>\n";	}
			else if ($_POST['group_num'] == $_SESSION['group_num'])
			{
				if ($_POST['group_mod'] == '0')
				{
					$database->query('INSERT groups (name) values ("'.addslashes($_POST['group_name']).'");');
					echo "Nowa grupa został utworzona.<br />\n";
				}
				else
				{
					$database->query('UPDATE groups SET name="'.addslashes($_POST['group_name']).
										'" WHERE id = '.$_POST['group_mod'].' ;');
					echo "Nazwa grupy została zmieniona.<br />\n";
				}
			}
			$_SESSION['group_num']++;			
		}
		else if (isset($_POST['del_group_butt']) && $_SESSION['group_num'] == $_POST['group_num'])
		{
			$database->query('DELETE FROM groups WHERE id='.$_POST['group_del'].' ;');
			echo 'Grupa została usunięta.<br/>'."\n";
			$_SESSION['group_num']++;
		}

		else if (isset($_GET['group_mod']) && is_numeric($_GET['group_mod']))
		{
			$result = $database->query('SELECT name FROM groups WHERE id = '.$_GET['group_mod'].';');
			$row = $result->fetch_assoc();
			$group_txt = 'Nowa nazwa dla grupy "'.$row['name'].'" :';
			$gr_name = $row['name'];
			$mod_id = $_GET['group_mod'];
			$send_txt = 'Zmień';
		}
		
		else if (isset($_GET['group_del']) && is_numeric($_GET['group_del']))
		{
			$result = $database->query('SELECT name FROM groups WHERE id = '.$_GET['group_del'].';');
			$row = $result->fetch_assoc();
			echo 'Czy na pewno chcesz usunąć grupę: '.$row['name']." ?<br/>\n";	?>
<center><table id="clear"><tr><td>
<form action="?category=admin/groups_wt" method="POST">
<input type="hidden" name="group_del" value="<?php	echo $_GET['group_del'];	?>" />
<input type="hidden" name="group_num" value="<?php	echo $_SESSION['group_num'];	?>" />
<input type="submit" value="Usuń" name="del_group_butt" />
</form></td><td>
<form action="?category=admin/groups_wt" method="POST">
<input type="submit" value="Cofnij" name="bac_group_butt"/>
</form></td></tr></table></center><br/><hr/><br/>
<?php	
		}
		else if (isset($_GET['group_chk_id']) && is_numeric($_GET['group_chk_id']))
		{
			$result = $database->query('SELECT DISTINCT * FROM (SELECT solutions.user_id, solutions.task_id, solutions.id FROM task_to_group RIGHT JOIN solutions ON task_to_group.task_id = solutions.task_id LEFT JOIN user_to_group ON solutions.user_id = user_to_group.user_id WHERE user_to_group.group_id = '.$_GET['group_chk_id'].' AND task_to_group.group_id = '.$_GET['group_chk_id'].' ORDER BY id DESC) AS tab GROUP BY tab.user_id, tab.task_id;');
			$numberRows = $result->num_rows;
			$args = '';
			for ($i=0; $i < $numberRows; $i++)
			{
				$row = $result->fetch_assoc();
				$args = $args."'".$row['id']."' ";
			}
			$result = $database->query("UPDATE task_to_group SET last_chk = NOW() WHERE group_id = ".$_GET['group_chk_id']." ;");
			echo 'Rozwiązania zostały wysłane do sprawdzenia...<br/><br/>';
			exec('sh -c "../spoj_engine2 '.$args.'"');
		}		
		
		$result = $database->query("SELECT id, name FROM groups ORDER BY name ;");
		$numberRows = $result->num_rows;
		if ($numberRows > 0)
		{
			echo '<div id="group_list">'."\n".'<b>Edytuj jedną z istniejących grup:</b>'.
				'<br/><br/><table id="group_list">'."\n";
			for ($i=0; $i < $numberRows; $i++)
			{
				$row = $result->fetch_assoc();
				echo '<tr><td id="name"><a href="?category=admin/group_edit&group_id='.$row['id'].'">'.$row['name'].'</a></td>'."\n";
				echo '<td id="chk"><a href="?category=admin/groups_wt&group_chk_id='.$row['id'].'">sprawdź zadania</a></td>'."\n";
				echo '<td id="chn"><a href="?category=admin/groups_wt&group_mod='.$row['id'].'">zmień nazwę</a></td>'."\n";
				echo '<td id="rem"><a href="?category=admin/groups_wt&group_del='.$row['id'].'">usuń</a></td></tr>'."\n";
			}
			echo '</table></div><br/><hr/><br/>'."\n";
		}
		else	{	$groups_tit2 = 'Dodaj nową grupę:';	}
			
?>
<div id="group_add">
<b><?php	echo $groups_tit2	?></b><br/><br/>
<table id="clear"><tr>
<form action="?category=admin/groups_wt" method="POST" >
<input type="hidden" name="group_num" value="<?php	echo $_SESSION['group_num'];	?>" />
<input type="hidden" name="group_mod" value="<?php	echo $mod_id;	?>" />
<?php	echo $group_txt;	?><br/>
<td><input id="group_name" type="text" name="group_name" maxlength="255" value="<?php	echo $gr_name;	?>"/></td>
<td><input type="submit" value="<?php	echo $send_txt	?>" name="group_butt" /></td>
</form><?php
if ($mod_id)	{	?>
<form action="?category=admin/groups_wt" method="POST" >
<td><input type="submit" value="Czyść" name="group_clear" /></td><?php	}	?>
</tr></table>
</div>
<br/><br/><hr/><br/><center>
<a id="topper" href="?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		$database->close();
	}
} else {	echo "Nie masz uprawnien do przeglądania tej strony.<br />\n";	}?>
</p>
