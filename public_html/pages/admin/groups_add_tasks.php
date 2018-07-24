<h3>Dodaj zadania do grupy</h3>

<p>
<?php
if ($_SESSION['isAdmin'])
{
	$database = connectDatabase();
	if (!$database)	{	include('php/database_fail.php');	}
	else
	{
	if (isset($_GET['group_id']))
	{
		$group = "&group_id=".$_GET['group_id'];
		if (isset($_POST['add_tasks']) && $_SESSION['group_tsk_num'] == $_POST['group_tsk_num'])
		{
			$database->query('DELETE FROM task_to_group WHERE group_id = '.$_POST['group_id'].' ;');
			$i=0;
			$adds_id='('.$_POST['group_id'].','.$_POST['task_id'][$i++].') ';
			while (!empty($_POST['task_id'][$i]))
				{	$adds_id=$adds_id.' , ('.$_POST['group_id'].','.$_POST['task_id'][$i++].') ';	}
			
			$database->query('INSERT task_to_group (group_id, task_id) VALUES '.$adds_id.';');
			$_SESSION['group_tsk_num']++;
			echo "Zadania w grupie zostały zaktualizowane.<br />\n";
		}
		else if (isset($_POST['add_from_group']) && $_SESSION['group_tsk_num'] == $_POST['group_tsk_num'])
		{
			if ($_POST['group_add_b'] == '0')
			{
				$database->query('DELETE deleted FROM task_to_group deleted, task_to_group looked WHERE deleted.group_id='.
								$_POST['group_id'].' AND looked.group_id='.$_POST['added_group'].
								' AND deleted.task_id = looked.task_id;');
				echo "Zadania zostały usunięte z grupy.<br />\n";
			}
			else
			{
				$database->query("INSERT task_to_group SELECT task_id, ".$_POST['group_id'].
						" AS group_id, NULL AS last_chk from task_to_group where group_id = ".$_POST['added_group'].";");
				echo "Zadania zostały dodane do grupy.<br />\n";
			}
			$_SESSION['group_tsk_num']++;
		}

		if (!isset($_SESSION['group_tsk_num']))	{	$_SESSION['group_tsk_num'] = 0;	}

		$back_addr = "?category=admin/groups_add_tasks&group_id=".$_GET['group_id'];
		$back_addr2 = "?category=admin/groups_add_tasks2&group_id=".$_GET['group_id'];

		$sort = "id";
		if (isset($_POST['sort'])) {	$sort = $_POST['sort'];	}

		$result = $database->query("SELECT name FROM groups WHERE id = ".$_GET['group_id'].";");
		$row = $result->fetch_assoc();
		$groupName = $row['name'];
		
		$result = $database->query("SELECT id, name FROM groups WHERE id != ".$_GET['group_id'].";");
		$numberRows = $result->num_rows;
		$groupList = "";
		for ($i=0; $i < $numberRows; $i++)
		{
			$row = $result->fetch_assoc();
			$groupList = $groupList.'<option value="'.$row['id'].'" >'.$row['name']."</option>\n";
		}

		$spacer = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$spacer = $spacer.$spacer.$spacer.$spacer.$spacer.$spacer.$spacer.$spacer.$spacer.$spacer;
		$len1=4;
		$len2=70;
?>

<form id="ch_group" action="index.php" method="get">
<input type="hidden" name="category" value="admin/groups_add_tasks" />
<b>Wybierz zadania dla grupy <?php	echo $groupName; ?></b><br/>lub zmień grupę: 
<form id="ch_group" action="index.php" method="get">
<select id="ch_group" name="group_id">
<?php	echo $groupList;	?></select>
<input type="submit" value="Zmień"/></form><br/>


<center>
<form id="grta_sel" action="<?php	echo $back_addr;	?>" method="post">
<input type="hidden" name="group_tsk_num" value="<?php	echo $_SESSION['group_tsk_num'];	?>" />
<input type="hidden" name="group_id" value="<?php	echo $_GET['group_id'];	?>" />
<table id="clear"><tr><td>
<select id="grta_sel" multiple="multiple" size="24" name="task_id[]">
<option id="grta_sel" value="0" disabled="disabled">&nbsp; Id | &nbsp; &nbsp; Tytuł zadania</option>
<option id="grta_sel" value="0" disabled="disabled">
-----+--------------------------------------------------------------------
</option>
<?php
 		$result = $database->query("SELECT taskList.id, title, group_tab.group_id AS 'group' FROM taskList LEFT JOIN (SELECT * FROM task_to_group WHERE group_id = ".$_GET['group_id'].") AS group_tab ON taskList.id = group_tab.task_id ORDER BY ".$sort.";");
		$numberRows = $result->num_rows;
		for ($i=0; $i < $numberRows; $i++)
		{
			$row = $result->fetch_assoc();
			if ($row['group'])
				{	echo '<option id="grta_sel" value="'.$row['id'].'" selected="selected"> ';	}
			else	
				{	echo '<option id="grta_sel" value="'.$row['id'].'"> ';	}
			echo substr($spacer, 0, ($len1-strlen($row['id']))*6).$row['id'].'.| ';
			if (strlen($row['title']) > ($len2-3))
				{	echo substr($row['title'], 0, $len2-6).'...';	}
			else
				{	echo $row['title'];	}
			echo ' </option>'."\n";
		}
?>
<option id="grta_sel" value="0" disabled="disabled">
-----+--------------------------------------------------------------------
</option>
</select></td><td>
<center><br/><br/>
Sortuj<br/>według:<br/>
<form id="grta_sel" action="<?php	echo $back_addr;	?>" method="post">
<input type="hidden" name="sort" value="id" />
<input type="submit" value=" Numer " name="sort_tasks"/></form>
<form id="grta_sel" action="<?php	echo $back_addr;	?>" method="post">
<input type="hidden" name="sort" value="title" />
<input type="submit" value=" Tytuł " name="sort_tasks"/></form>
</td></tr></table><br/>
<center><table id='clear'><tr><td><input type="submit" value="Zatwierdź" name="add_tasks"/></td><td>
<form id="clear_sel" action="<?php	echo $back_addr;	?>" method="post">
<input type="submit" value="Przywróć"/></form></td></tr></table>
</center>
</form>
</center><br/><hr/><br/>

<b>Lub dodaj/usuń wszystkie zadania z grupy juz istniejącej: </b><br/><br/>
<center>
<form id="add_from_group" action="<?php	echo $back_addr;	?>" method="post">
<input type="hidden" name="group_tsk_num" value="<?php	echo $_SESSION['group_tsk_num'];	?>" />
<input type="hidden" name="group_id" value="<?php	echo $_GET['group_id'];	?>" />
<table id="clear"><tr><td colspan="2">
<input type="radio" value="1" name="group_add_b" checked="checked"> dodaj</input>
<input type="radio" value="0" name="group_add_b"> usuń</input>
</td></tr><tr><td>grupa: 
<select id="add_from_group" name="added_group">
<?php	echo $groupList;	?></select> <br/> 
 </td><td> 
<input type="submit" value="Dodaj / Usuń" name="add_from_group"/></td></tr></table></form>
</center>
<?php
	}
	else
	{
	?>
<br/><br/><center><b>Wybierz grupę którą chcesz edytować.</b><br/>
<form id="ch_group" action="index.php" method="get">
<input type="hidden" name="category" value="admin/groups_add_tasks" /><br/>
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
<a id="topper" href="?category=admin/group_edit<?php if (isset($_GET['group_id'])) { echo "&group_id=".$_GET['group_id'];}	?>">&#8592; Do info grupy</a>
<a id="topper" href="?category=admin/groups_wt">&#8592; Do wyboru grupy</a>
<a id="topper" href="?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		$database->close();
	}
} else {	echo "Nie masz uprawnien do przeglądania tej strony.<br />\n";	}?>
</p>
