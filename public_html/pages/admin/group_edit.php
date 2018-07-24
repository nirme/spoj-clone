<?php
if ($_SESSION['isAdmin'])
{
	$database = connectDatabase();
	if (!$database)	{	include('php/database_fail.php');	}
	else
	{
		if (isset($_GET['group_id']))
		{
			$group_id = $_GET['group_id'];
			$result = $database->query("SELECT name FROM groups WHERE id = ".$group_id.";");
			$row = $result->fetch_assoc();
			$group_name = $row['name'];

?><h3>Informacje o grupie: <b><?php	echo $group_name;	?></b></h3><?php

			if (isset($_GET['task_chk_id']) && is_numeric($_GET['task_chk_id']))
			{
				$result = $database->query('SELECT DISTINCT * FROM (SELECT solutions.id, solutions.user_id, solutions.task_id FROM task_to_group RIGHT JOIN solutions ON task_to_group.task_id = solutions.task_id LEFT JOIN user_to_group ON solutions.user_id = user_to_group.user_id WHERE solutions.task_id = '.$_GET['task_chk_id'].' AND user_to_group.group_id = '.$_GET['group_id'].' AND task_to_group.group_id = '.$_GET['group_id'].' ORDER BY id DESC) AS tab GROUP BY tab.user_id, tab.task_id;');
				$numberRows = $result->num_rows;
				$args = '';
				$ups = '';
				for ($i=0; $i < $numberRows; $i++)
				{
					$row = $result->fetch_assoc();
					$args = $args."'".$row['id']."' ";
					if ($i)
						{	$ups = $ups.' OR ';	}
					$ups = $ups.'id = '.$row['id'];
				}
				$result = $database->query("UPDATE task_to_group SET last_chk = NOW() WHERE task_id = ".$_GET['task_chk_id'].";");
				echo 'Rozwiązania zostały wysłane do sprawdzenia...<br/><br/>';
				exec('sh -c "../spoj_engine2 '.$args.'"');
			}

			?>

<center><b>Użytkownicy: &nbsp; </b><a id="gr_edit" href="?category=admin/groups_add_users&group_id=<?php
echo $group_id	?>">Edytuj</a></center>
<table id="groups_lists" cellspacing="0" cellpadding="0">
<tr><th id="left">Indeks</th><th>Nazwisko</th><th>Imię</th><th>Login</th><th id="right">Mail</th></tr>
<?php
			$result = $database->query("SELECT indeks, name, surname, login, mail FROM users LEFT JOIN user_to_group ON users.id = user_to_group.user_id WHERE group_id = ".$_GET['group_id']." ORDER BY surname;");
			$numberRows = $result->num_rows;
			for ($i=0; $i < $numberRows; $i++)
			{
				$row = $result->fetch_assoc();
				if (strlen($row['mail']) > 20) {	$mail_short = substr($row['mail'], 0, 17).'...';	}
				else {	$mail_short = $row['mail'];	}
				echo '<tr><td id="num">'.$row['indeks'].'</td><td id="surname">'.$row['surname'].'</td><td id="name">'.$row['name'].
				'</td><td id="login">'.$row['login'].'</td><td id="mail"><a href="mailto:'.$row['mail'].'">'.$mail_short.'</a></td></tr>'."\n";
			}
			?>
</table><br/><hr/><br/>

<center><b>Zadania:</b> &nbsp; <a id="gr_edit" href="?category=admin/groups_add_tasks&group_id=<?php
echo $group_id	?>">Edytuj</a></center>
<table id="groups_lists" cellspacing="0" cellpadding="0">
<tr><th id="left">Id</th><th>Nazwa zadania</th><th id="right" colspan="2">Sprawdzono</th></tr>
<?php
			$result = $database->query("SELECT id, title, last_chk FROM taskList LEFT JOIN task_to_group ON taskList.id = task_to_group.task_id WHERE group_id = ".$_GET['group_id']." ORDER BY id;");
			$numberRows = $result->num_rows;
			for ($i=0; $i < $numberRows; $i++)
			{
				$row = $result->fetch_assoc();
				echo '<tr><td id="id">'.$row['id'].'</td><td id="title"><a href="?category=admin/groups_task_stat&task_id='.$row['id'].
					'&group_id='.$_GET['group_id'].'">'.$row['title'].'</a></td><td id="last_chk">'.$row['last_chk'].
					'</td><td id="spr"><a href="?category=admin/group_edit&group_id='.$_GET['group_id'].'&task_chk_id='.
					$row['id'].'">Sprawdź</a></td></tr>'."\n";
			}
			?>
</table>

<?php
		}
		else
		{
		?>
<br/><br/><center><b>Wybierz grupę którą chcesz edytować.</b><br/>
<form id="ch_group" action="index.php" method="get">
<input type="hidden" name="category" value="admin/group_edit" /><br/>
<select id="ch_group" name="group_id">
<?php
		$result = $database->query("SELECT id, name FROM groups ;");
		$numberRows = $result->num_rows;
		for ($i=0; $i < $numberRows; $i++)
		{
			$row = $result->fetch_assoc();
			echo '<option value="'.$row['id'].'" >'.$row['name']."</option>\n";
		}
?></select>
<input type="submit" value="Wybierz"/></form></center><br/><br/><br/>
<?php
		}
		?>
<br/><hr/><br/><center>
<a id="topper" href="?category=admin/groups_wt">&#8592; Do wyboru grupy</a>
<a id="topper" href="javascript:scroll(0,0)">&#8593; Do góry &#8593;</a>
<a id="topper" href="?category=admin/adminPanel">&#8593; Wroć do panelu &#8593;</a>
</center>
<?php
		$database->close();
	}
} else {	echo "Nie masz uprawnien do przeglądania tej strony.<br />\n";	}?>
