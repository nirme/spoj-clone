<h3>Dodaj język programowania</h3>
<p>
<?php
if ($_SESSION['isAdmin'])
{
	$database = connectDatabase();
	if (!$database)	{	include('php/database_fail.php');	}
	else
	{
		if (!isset($_SESSION['lang_num']))
			{	$_SESSION['lang_num']=1;	}
		$ok_flag = false;
		$sub_title = "";
		$lang_name = $_POST['lang_name'];
		$lang_compiler = $_POST['lang_compiler'];
		$lang_file = $_POST['lang_file'];
		$lang_comp_str = $_POST['lang_comp_str'];

		if (isset($_POST['lang_button']))
		{
			if (empty($_POST['lang_name']))
				{	$sub_title = 'Nie podałeś nazwy języka';	}
			else if (empty($_POST['lang_compiler']))
				{	$sub_title = 'Nie podałeś nazwy / wersji kompilatora';	}
			else if (empty($_POST['lang_file']))
				{	$sub_title = 'Nie podałeś dopuszczalnych rozszerze plików źródłowych';	}
			else if ($_POST['lang_is_script'] != "TRUE" && empty($_POST['lang_comp_str']))
				{	$sub_title = 'Nie podałeś stringu kompilacji';	}
			else if ($_SESSION['lang_num'] != $_POST['lang_num'])
			{
				echo "<br/><center>";
				echo "Na tej stronie nie można odświerzać...<br/>\n";
				$ok_flag = true;
				echo "<br/><br/>\n";
				echo '<a id="topper" href="index.php?category=admin/news">&#8592; Wróć</a>';
				echo '<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>'."\n";
				echo "</center>\n";
			}
			else if ($_SESSION['langs'] == 1)
			{
				echo "<br/><center>";
				$ok_flag = true;
				$is_script = "FALSE";
				$comp_str = $_POST['lang_comp_str'];
				if ($_POST['lang_is_script'] == "TRUE")
				{
					$is_script = "TRUE";
					$comp_str = "";
				}
				if (!$_POST['lang_mod_id'] || $_POST['lang_mod_id'] == '0')
				{
					$database->query('INSERT languages (language_name, compiler_system_name, file_format, compile_string, script_language) VALUES ("'.
									addslashes($_POST['lang_name']).'", "'.
									addslashes($_POST['lang_compiler']).'", "'.
									addslashes($_POST['lang_file']).'", "'.
									addslashes($comp_str).'", '.
									$is_script.');');
					echo "Język dodano do bazy danych.<br />\n";
				}
				else
				{
					$database->query('UPDATE languages SET language_name="'.addslashes($_POST['lang_name']).
										'", compiler_system_name="'.addslashes($_POST['lang_compiler']).
										'", file_format="'.addslashes($_POST['lang_file']).
										'", compile_string="'.addslashes($comp_str).
										'", script_language='.$is_script.
										' WHERE id = '.$_POST['lang_mod_id'].' ;');
					echo "Język został zmieniony.<br />\n";
				}
				echo "<br/><br/>\n";
				echo '<a id="topper" href="index.php?category=admin/langs">&#8592; Wróć</a>';
				echo '<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>'."\n";
				echo "</center>\n";
				$_SESSION['langs'] = 2;
				$_SESSION['lang_num'] = $_SESSION['lang_num']+1;
			}
		}
		else if (isset($_POST['lang_del_button']))
		{
			$ok_flag = true;
			$result4 = $database->query("SELECT language_name, compiler_system_name FROM languages WHERE id = ".$_POST['lang_mod_id'].' ;');
			$row4 = $result4->fetch_assoc();
			echo "Czy na pewno chcesz usunąć język: <br/>\n";
			echo $row4['language_name']." [".$row4['compiler_system_name']."] <br/>\n";
?>
<br/><br/>
<center><table id="clear"><tr>
<td><form action="?category=admin/langs" method="POST" >
<input type="submit" value="Cofnij" name="lang_del_err_but" />
</form></td>
<td><form action="?category=admin/langs" method="POST" >
<input type="hidden" name="lang_mod_id" value="<?php	echo $_POST['lang_mod_id'];	?>" />
<center><input type="submit" value="Usuń" name="lang_del_ok_but" /></center>
</form></td></tr></table><br/>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		}
		else if (isset($_POST['lang_del_ok_but']))
		{
			$ok_flag = true;
			$database->query('DELETE FROM languages WHERE id='.$_POST['lang_mod_id'].' ;');
			echo "Język został usunięty\n";
		}
		else if (isset($_POST['lang_mod_button']))
		{
			$sub_title = 'Zmień newsa';
			$result3 = $database->query("SELECT * FROM languages WHERE id = ".$_POST['lang_mod_id'].' ;');
			$row3 = $result3->fetch_assoc();
			$lang_name = $row3['language_name'];
			$lang_compiler = $row3['compiler_system_name'];
			$lang_file = $row3['file_format'];
			$lang_comp_str = $row3['compile_string'];
			$lang_is_script = $row3['script_language'];
		}		
		if (!$ok_flag)
		{
			$_SESSION['langs'] = 1;
			echo $sub_title;
			if (!empty($sub_title))	
				{	echo ":\n";	}
			else
				{	echo "<br />\n";	}	?>
<form action="?category=admin/langs" method="POST" >
<input type="hidden" name="lang_num" value="<?php	echo $_SESSION['lang_num'];	?>" />
<input type="hidden" name="lang_mod_id" value="<?php
		if (isset($_POST['lang_mod_id']))
			{	echo $_POST['lang_mod_id'];	}
		else
			{	echo '0';	}
?>" />
<table id="clear">
<tr><td>
Nazwa języka: <br/>
<input id="lang_name" type="text" name="lang_name" maxlength="32" value="
<?php	echo $lang_name;	?>
"></input></td>
<td>
Nazwa i wersja kompilatora: <br/>
<input id="lang_compiler" type="text" name="lang_compiler" maxlength="32" value="
<?php	echo $lang_compiler;	?>
"></input></td>
<td rowspan="3">
Formaty źródła: <br/>
<textarea id="lang_file" name="lang_file" maxlength="25">
<?php	echo $lang_file;	?>
</textarea></td></tr>
<tr><td colspan="2">
<center><input type="checkbox" id="lang_is_script" name="lang_is_script" value="TRUE"<?php
if ($lang_is_script == 1)	{	echo 'checked="checked"';	}
?>/> Język skryptowy</center>
Komenda kompilacji (plik wejściowy - %1, plik wyjściowy %2): <br/>
<textarea id="lang_comp_str" type="text" name="lang_comp_str" maxlength="255">
<?php	echo $lang_comp_str;	?>
</textarea>
</td></tr></table>
<center><input type="submit" value="Wyślij" name="lang_button" /></center>
</form><br/><hr/><br/><center>
Wybierz język do usunięcia lub edycji: 
<br/><br/>
<?php
			$result2 = $database->query("SELECT id, compiler_system_name, language_name FROM languages ORDER BY language_name;");
			$numberRows = $result2->num_rows;
			$langs = "";
			$row2 = $result2->fetch_assoc();
			$langs = $langs."<option value=\"".$row2['id']."\" selected=\"selected\">".$row2['language_name'].' ['.$row2['compiler_system_name'].']'."</option>\n";
			for ($i=1; $i < $numberRows; $i++)
			{
				$row2 = $result2->fetch_assoc();
				$langs = $langs."<option value=\"".$row2['id']."\">".$row2['language_name'].' ['.$row2['compiler_system_name'].']'."</option>\n";
			}
?>
<form action="?category=admin/langs" method="POST" >
<input type="hidden" name="lang_num" value="<?php	echo $_SESSION['lang_num'];	?>" />
<select name="lang_mod_id" id="lang_edde">
<?php	echo $langs;	?>
</select><br/><br/>
<input type="submit" value="Usuń" name="lang_del_button" /> <input type="submit" value="Zmień" name="lang_mod_button" />
</form></center><br/>
<hr/>
<br/><center>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		}
		$database->close();
	}
} else {	echo "Nie masz uprawnien do przeglądania tej strony.<br />\n";	}?>
</p>
