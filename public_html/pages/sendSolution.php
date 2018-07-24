<h1>Wyślij rozwiązanie</h1>

<?php
$database = connectDatabase();
if (!$database)
	{	include('php/database_fail.php');	}
else if (!isset($_SESSION['userId']))
	{	loginForm($category, $authStatus);	}
else {
	if ($_SESSION['isAdmin']) {
		$result = $database->query('SELECT id, title FROM taskList '.$page.';');
	}
	else {
		$result = $database->query('SELECT DISTINCT taskList.id, title FROM groups JOIN user_to_group ON groups.id = user_to_group.group_id JOIN task_to_group ON task_to_group.group_id = groups.id JOIN taskList ON taskList.id = task_id WHERE user_id = '.$_SESSION['userId'].' GROUP BY taskList.id ;');
	}
	$numberRows = $result->num_rows;
	$task_id = 1;
	if (!empty($_GET['task_id']))
		{	$task_id = $_GET['task_id'];	}
	$tasks = "";
	for ($i = 0; $i < $numberRows; $i++)
	{
		$row = $result->fetch_assoc();
		if ( $row['id'] != $task_id )
			{	$tasks = $tasks."<option value=\"".$row['id']."\">".$row['id'].'. '.$row['title']."</option>\n";	}
		else
			{	$tasks = $tasks."<option value=\"".$row['id']."\" selected=\"selected\">".$row['id'].'. '.$row['title']."</option>\n";	}
	}
	$result = $database->query("SELECT id, language_name, compiler_system_name FROM languages ORDER BY id;");
	$numberRows = $result->num_rows;
	$langs = "";
	if ($numberRows > 0)
	{
		$row = $result->fetch_assoc();
		$langs = $langs."<option value=\"".$row['id']."\" selected=\"selected\">".$row['language_name']." (".$row['compiler_system_name'].")</option>\n";
	}
	for ($i = 1; $i < $numberRows; $i++)
	{
		$row = $result->fetch_assoc();
		$langs = $langs."<option value=\"".$row['id']."\">".$row['language_name']." (".$row['compiler_system_name'].")</option>\n";
	}
	$database->close();
	$_SESSION['solution'] = true; 
?>

<p>
<form enctype="multipart/form-data" action="index.php?category=sendSolution_uploader" method="POST" >
Treść:<br />
<textarea id="task_text" name="solution_text"></textarea><br />
Zadanie: 
<select name="task_id">
<?php	echo $tasks;	?>
</select>
<br/><br/>
Język: 
<select name="lang_id">
<?php	echo $langs;	?>
</select>
<br/><br/>
Lub wyślij plik z rozwiązaniem:<br />
<input type="hidden" name="MAX_FILE_SIZE" value="10240" />
<label for="file">Plik:</label>
<input type="file" name="solution_file" /> 
<br /><br />
<center><input type="submit" value="Wyślij rozwiązanie" name="solution_button"/></center>
</form>

</p>

<?php	}	?>
