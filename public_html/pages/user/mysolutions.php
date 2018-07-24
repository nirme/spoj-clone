<h1>Moje rozwiązania</h1>
<?php
$database = connectDatabase();
if (!$database)
{	include('php/database_fail.php');	}
else if (!isset($_SESSION['userId']))
	{	loginForm($category, $authStatus);	}
else
{
	$limitSize = 100;
	$result = $database->query("SELECT COUNT(id) AS 'pages' FROM solutions;");
	$row = $result->fetch_assoc();
	$page_count = intval($row['pages'] / $limitSize);
	if ($row['pages'] % $limitSize)
		{	$page_count = $page_count + 1;	}
		
	if (!empty($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $page_count)
	{
		$page = ' LIMIT '.$limitSize.' OFFSET '.(($_GET["page"]-1)*$limitSize);
		$actual_page = $_GET["page"];
	}
	else
	{
		$page = ' LIMIT '.$limitSize.' OFFSET 0';
		$actual_page = 1;
	}

	$result = $database->query("SELECT solutions.id, solutions.task_id, solutions.make_date, solutions.lang_id,
		solutions.points, solutions.error, solutions.error_str, taskList.title AS 'task_title',
		languages.language_name AS 'lang_name', languages.compiler_system_name AS 'compiler'
		FROM solutions LEFT JOIN taskList ON solutions.task_id = taskList.id
		LEFT JOIN languages ON solutions.lang_id = languages.id
		WHERE solutions.user_id = ".$_SESSION['userId'].
		" ORDER BY make_date DESC ".$page.' ;');
	$numberRows = $result->num_rows;
?>
<table id="task_list"> <!-- change to sol_list -->
<tr><th>task_title</th><th>lang_name</th><th>make_date</th>
<th>points</th><th>error</th></tr>
<?php
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

	for ($i = 0; $i < $numberRows; $i++)
	{
		$row = $result->fetch_assoc();
		echo '<tr>';
		echo '<td>'.$row['task_title'].'</td>';
		echo '<td>'.$row['lang_name'].' ['.$row['compiler'].']</td>';
		echo '<td>'.$row['make_date'].'</td>';
		echo '<td>'.$row['points'].'</td>';
		echo '<td id="zero"><a id="zero" href="index.php?category=solutions_info&solution_id='.$row['id'].'">'.$err_array[$row['error']].'</td>'.'</a></td>'."\n";
		echo '</tr>'."\n";
	}
	echo "</table>\n\n";

	echo '<table id="paginator"><tr>'."\n";
	if ($actual_page != 1)
		{	echo '<td><a href="index.php?category=user/mysolutions&page='.($actual_page-1).'">&#8592;</a></td>';	}
	else
		{	echo '<td><p>&#8592;</p></td>';	}
	echo "\n";
	
	for ($i=1; $i <= $page_count; $i++)
	{	
		if ($actual_page != $i)
			{	echo '<td><a href="index.php?category=user/mysolutions&page='.$i.'">'.$i.'</a></td>';	}
		else
			{	echo '<td><p><b>'.$i.'</b></p></td>';	}
		echo "\n";
	}

	if ($actual_page != $page_count)
		{	echo '<td><a href="index.php?category=user/mysolutions&page='.($actual_page+1).'">&#8594;</a></td>';	}
	else
		{	echo '<td><p>&#8594;</p></td>';	}
	echo '</tr></table>'."\n\n";
		
	$database->close();
}
?>
