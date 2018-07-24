<h1>Zadanie</h1>
<?php
$database = connectDatabase();
if (!$database)
	{	include('php/database_fail.php');	}
else if (isset($_GET['task_id']) && is_numeric($_GET['task_id']))
{
	$result = $database->query('SELECT * FROM task_to_group JOIN user_to_group ON task_to_group.group_id = user_to_group.group_id WHERE task_id = '.$_GET['task_id'].' AND user_id = '.$_SESSION['userId'].';');
	if ($result->num_rows || $_SESSION['isAdmin'])
	{
		$result = $database->query("SELECT id, makerId, points, title, makeDate, runTime, description FROM taskList WHERE id=".$_GET['task_id'].";");
		$row = $result->fetch_assoc();
		
		$author = $database->query('SELECT name, surname FROM users WHERE id='.$row['makerId'].' ;');
		$autName = $author->fetch_assoc();
		
		echo '<h3>'.$row['id'].'. '.$row['title']."</h3><br/>\n";
		echo '<p id="task_desc">'.$row['description']."</p><hr/>\n";
		echo '<table id="task_info">';
		echo '<tr><td>Dodany przez: </td><td>'.$autName['name'].' '.$autName['surname']."</td></tr>\n";
		echo '<tr><td>Data: </td><td>'.$row['makeDate']."</td></tr>\n";
		echo '<tr><td>Limit czasu: </td><td>'.($row['runTime'] / 1000)."s</td></tr>\n";
		echo '<tr><td>Punkty: </td><td>'.$row['points']."</td></tr>\n";
		echo "</table><hr/><br/>\n";

		$back_page = "";
		if (!empty($_GET['page']))
			{	$back_page = '&page='.$_GET['page'];	}
		echo '<center>';
		echo '<a id="topper" href="index.php?category=tasks'.$back_page.'">&#8592; Wróć do zadań</a>'."\n";
		echo '<a id="topper" href="javascript:scroll(0,0)">&#8593; Do góry &#8593;</a>'."\n";
		echo '<a id="topper" href="index.php?category=sendSolution&task_id='.$_GET['task_id'].'">Wyślij rozwiązanie&#8594;</a>'."\n";
		echo '</center>';
	}
	else
		{	echo "no to frugo...<br\>\n";	}		
	$database->close();
}
else
{
	echo "no to frugo...<br\>\n";
}


?>

