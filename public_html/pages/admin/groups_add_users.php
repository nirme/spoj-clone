<h3>Dodaj użytkowników do grupy</h3>

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
		if (isset($_POST['add_users']) && $_SESSION['group_usr_num'] == $_POST['group_usr_num'])
		{
			$database->query('DELETE FROM user_to_group WHERE group_id = '.$_POST['group_id'].' ;');
			
			$i=0;
			$adds_id='('.$_POST['group_id'].','.$_POST['user_id'][$i++].') ';
			while (!empty($_POST['user_id'][$i]))
				{	$adds_id=$adds_id.' , ('.$_POST['group_id'].','.$_POST['user_id'][$i++].') ';	}
			
			$database->query('INSERT user_to_group (group_id, user_id) VALUES '.$adds_id.';');
			$_SESSION['group_usr_num']++;
			echo "Użytkownicy w grupie zostali zaktualizowani.<br />\n";
		}
		else if (isset($_POST['add_from_group']) && $_SESSION['group_usr_num'] == $_POST['group_usr_num'])
		{
			if ($_POST['group_add_b'] == '0')
			{
				$database->query('DELETE deleted FROM user_to_group deleted, user_to_group looked WHERE deleted.group_id='.
								$_POST['group_id'].' AND looked.group_id='.$_POST['added_group'].
								' AND deleted.user_id = looked.user_id;');
				echo "Użytkownicy zostali usunięci z grupy.<br />\n";
			}
			else
			{
				$database->query("INSERT user_to_group SELECT ".$_POST['group_id'].
				" AS 'group_id', user_id from user_to_group where group_id=".$_POST['added_group'].";");
				echo "Użytkownicy zostali dodani do grupy.<br />\n";
			}
			$_SESSION['group_usr_num']++;
		}
		
		if (!isset($_SESSION['group_usr_num']))	{	$_SESSION['group_usr_num'] = 0;	}

		$back_addr = "?category=admin/groups_add_users&group_id=".$_GET['group_id'];
		$back_addr2 = "?category=admin/groups_add_users2&group_id=".$_GET['group_id'];

		$sort = "indeks";
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

		$result = $database->query("SELECT id, name, surname, indeks, group_tab.group_id AS 'group' FROM users LEFT JOIN (SELECT * FROM user_to_group WHERE group_id = ".$_GET['group_id'].") AS group_tab ON users.id = group_tab.user_id WHERE indeks > 0 ORDER BY ".$sort.";");
		$numberRows = $result->num_rows;
		
		
		$spacer = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$spacer = $spacer.$spacer.$spacer.$spacer.$spacer.$spacer.$spacer.$spacer.$spacer.$spacer;
		$len1=11;
		$len2=18;
		$len3=16;
?>
<form id="ch_group" action="index.php" method="get">
<input type="hidden" name="category" value="admin/groups_add_users" />
<b>Wybierz użytkowników dla grupy <?php	echo $groupName; ?></b><br/>lub zmień grupę: 
<form id="ch_group" action="index.php" method="get">
<select id="ch_group" name="group_id">
<?php	echo $groupList;	?></select>
<input type="submit" value="Zmień"/></form><br/>

<center>
<form id="grus_sel" action="<?php	echo $back_addr;	?>" method="post">
<input type="hidden" name="group_usr_num" value="<?php	echo $_SESSION['group_usr_num'];	?>" />
<input type="hidden" name="group_id" value="<?php	echo $_GET['group_id'];	?>" />
<table id="clear"><tr><td>
<select id="grus_sel" multiple="multiple" size="24" name="user_id[]">
<option id="grus_sel" value="0" disabled="disabled"> &nbsp; &nbsp; Indeks &nbsp; | &nbsp; &nbsp; &nbsp;Nazwisko&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp; Imię</option>
<option id="grus_sel" value="0" disabled="disabled">-------------+--------------------+-------------------</option>
<?php	for ($i=0; $i < $numberRows; $i++)
		{
			$row = $result->fetch_assoc();
			if ($row['group'])
				{	echo '<option id="grus_sel" value="'.$row['id'].'" selected="selected"> ';	}
			else	
				{	echo '<option id="grus_sel" value="'.$row['id'].'"> ';	}
			echo substr($spacer, 0, ($len1-strlen($row['indeks']))*6).$row['indeks'].' &nbsp;| '.
				substr($spacer, 0, ($len2-strlen($row['surname']))*6).$row['surname'].' | '.
				substr($spacer, 0, ($len3-strlen($row['name']))*6).$row['name'].' </option>'."\n";
		}
?>
<option id="grus_sel" value="0" disabled="disabled">-------------+--------------------+-------------------</option>
</select></td><td>
<center><br/><br/>
Sortuj<br/>według:<br/>
<form id="grus_sel" action="<?php	echo $back_addr;	?>" method="post">
<input type="hidden" name="sort" value="indeks" />
<input type="submit" value=" Indeks " name="sort_users"/></form>
<form id="grus_sel" action="<?php	echo $back_addr;	?>" method="post">
<input type="hidden" name="sort" value="surname" />
<input type="submit" value="Nazwisko" name="sort_users"/></form>
<form id="grus_sel" action="<?php	echo $back_addr;	?>" method="post">
<input type="hidden" name="sort" value="name" />
<input type="submit" value=" Imię " name="sort_users"/></form></center>
</td></tr></table><br/>
<center><table id='clear'><tr><td><input type="submit" value="Zatwierdź" name="add_users"/></td><td>
<form id="clear_sel" action="<?php	echo $back_addr;	?>" method="post">
<input type="submit" value="Przywróć"/></form></td></tr></table>
</center>
</form>
</center><br/><hr/><br/>

<b>Lub dodaj/usuń wszystki użytkowników z grupy juz istniejącej: </b><br/><br/>
<center>
<form id="add_from_group" action="<?php	echo $back_addr;	?>" method="post">
<input type="hidden" name="group_usr_num" value="<?php	echo $_SESSION['group_usr_num'];	?>" />
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
<input type="hidden" name="category" value="admin/groups_add_users" /><br/>
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
