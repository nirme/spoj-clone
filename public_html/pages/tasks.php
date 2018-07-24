<h1>Zadania</h1>
<p>
<?php
$database = connectDatabase();
if (!$database) {
include('php/database_fail.php');
	}
else
{
	?>
	<table id="task_list">
	<tr><th>ID</th><th>nazwa zadania</th><th>punkty</th><th>data</th></tr>
	<?php
	$limitSize = "20";
	$page = ' LIMIT '.$limitSize.' OFFSET 0';
	if (!empty($_GET["page"]))
	{
		if ($_GET["page"] != "all")
			{	$page = ' LIMIT '.$limitSize.' OFFSET '.(($_GET["page"]-1)*$limitSize); }
		else
			{	$page = "";	}
	}
	
	if ($_SESSION['isAdmin']) {
		$result = $database->query('SELECT taskList.id, title, points, makeDate FROM taskList '.$page.';');
	}
	else {
		$result = $database->query('SELECT DISTINCT taskList.id, title, points, makeDate FROM groups JOIN user_to_group ON groups.id = user_to_group.group_id JOIN task_to_group ON task_to_group.group_id = groups.id JOIN taskList ON taskList.id = task_id WHERE user_id = '.$_SESSION['userId'].' GROUP BY taskList.id '.$page.';');
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

	$result = $database->query("SELECT COUNT(id) AS 'pages' FROM taskList;");
	$row = $result->fetch_assoc();
	$page_get = 1;
	if (!empty($_GET["page"]))
		{	$page_get = $_GET["page"];	}
	$count = intval($row['pages'] / $limitSize);
	if ($row['pages'] % $limitSize)
		{	$count = $count + 1;	}
	echo '<table id="paginator"><tr>';
	if ($page_get > 1 && $page_get != "all")
		{	echo '<td><a href="index.php?category=tasks&page='.($page_get-1).'">&#8592;</a></td>';	}
	else
		{	echo '<td><p>&#8592;</p></td>';	}
	echo "\n";
	$flag = false;
	for ($i=1; $i <= $count; $i++)
	{	
		if ($page_get != $i)
			{	echo '<td><a href="index.php?category=tasks&page='.$i.'">'.$i.'</a></td>';	}
		else
			{	echo '<td><p><b>'.$i.'</b></p></td>';	}
		echo "\n";
	}
	if ($page_get < $count && $page_get != "all")
		{	echo '<td><a href="index.php?category=tasks&page='.($page_get+1).'">&#8594;</a></td>';	}
	else
		{	echo '<td><p>&#8594;</p></td>';	}
	echo "\n";
	if ($page_get != "all")
		{	echo '</tr>'."\n".'<tr><td id="all" colspan="'.($count+2).'"><a href="index.php?category=tasks&page=all">Wyświetl wszystko</a></td></tr></table>';	}
	else
		{	echo '</tr>'."\n".'<tr><td id="all" colspan="'.($count+2).'"><p><b>Wyświetl wszystko</b></p></td></tr></table>';	}
	
	$database->close();
	?>
	<?php	}	?>

</p>
