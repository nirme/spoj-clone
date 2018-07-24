<h3>Aktualności</h3>
<p>
<?php
if ($_SESSION['isAdmin'])
{
	$database = connectDatabase();
	if (!$database)	{	include('php/database_fail.php');	}
	else
	{
		if (!isset($_SESSION['news_num']))
			{	$_SESSION['news_num']=1;	}
		$ok_flag = false;
		$sub_title = '';
		$news_title = "";
		$news_cont = "";
		if (isset($_POST['news_add_button']))
		{
			if ( empty($_POST['news_titl']) || empty($_POST['news_cont']))
			{
				if (empty($_POST['news_titl']))
				{
					$sub_title = 'Nie wpisałeś tytułu nowej wiadomości';
					if (!empty($_POST['news_cont']))
						{	$news_cont = $_POST['news_cont'];	}
				}
				else
				{
					$sub_title = 'Nie wpisałeś treści nowej wiadomości';

					if (!empty($_POST['news_titl']))
						{	$news_title = $_POST['news_titl'];	}
				}
			}
			else if ($_SESSION['news_num'] != $_POST['news_num'])
			{
				echo "<br/><center>";
				echo "Na tej stronie nie można odświerzać...<br/>\n";
				$ok_flag = true;
				echo "<br/><br/>\n";
				echo '<a id="topper" href="index.php?category=admin/news">&#8592; Wróć</a>';
				echo '<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>'."\n";
				echo "</center>\n";
			}
			else if ($_SESSION['news'] == 1)
			{
				echo "<br/><center>";
				$ok_flag = true;
				if ($_POST['news_mod_num'] == '0' )
				{
					$database->query('INSERT news (title, news_text, author_id, clock) values ("'.
									addslashes($_POST['news_titl']).'", "'.
									addslashes(preg_replace("/\n/", "<br/>", strip_tags(trim($_POST['news_cont']), "<b><i><img><a>"))).
									'", '.$_SESSION['userId'].', NOW());');
					echo "Niusa dodano do bazy danych.<br />\n";
				}
				else
				{
					$database->query('UPDATE news SET title="'.addslashes($_POST['news_titl']).'", news_text="'.
						addslashes(preg_replace("/\n/", "<br/>", strip_tags(trim($_POST['news_cont']), "<b><i><img><a>"))).
						'" WHERE id = '.$_POST['news_mod_num'].' ;');
					echo "Niusa edytowano.<br />\n";
				}
				echo "<br/><br/>\n";
				echo '<a id="topper" href="index.php?category=admin/news">&#8592; Wróć</a>';
				echo '<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>'."\n";
				echo "</center>\n";
				$_SESSION['news'] = 2;
				$_SESSION['news_num'] = $_SESSION['news_num'] + 1;
			}
		}
		else if (isset($_POST['news_del_button']))
		{
			$ok_flag = true;
			$result4 = $database->query("SELECT title, clock, author_id FROM news WHERE id = ".$_POST['news_mod_num'].' ;');
			$row4 = $result4->fetch_assoc();
			$author = $database->query('SELECT name, surname FROM users WHERE id='.$row4['author_id'].' ;');
			$autName = $author->fetch_assoc();
			echo "Czy na pewno chcesz usunąć newsa<br/>\n";
			echo '<h4>"'.$row4['title']."\"</h4>\ndodanego ".$row4['clock'].' przez '.$autName['name'].' '.$autName['surname']." ?\n";
?>
<br/><br/>
<center><table id="clear"><tr>
<td><form action="?category=admin/news" method="POST" >
<input type="submit" value="Cofnij" name="news_del_err_but" />
</form></td>
<td><form action="?category=admin/news" method="POST" >
<input type="hidden" name="news_num" value="<?php	echo $_POST['news_mod_num'];	?>" />
<input type="submit" value="Usuń" name="news_del_ok_but" />
</form></td></tr></table><br/>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		}
		else if (isset($_POST['news_del_ok_but']))
		{
			$ok_flag = true;
			$database->query('DELETE FROM news WHERE id='.$_POST['news_num'].' ;');
			echo "<br/><center>";
			echo "News został usunięty\n";
			echo "<br/><br/>\n";
			echo '<a id="topper" href="index.php?category=admin/news">&#8592; Wróć</a>';
			echo '<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>'."\n";
			echo "</center>\n";
		}
		else if (isset($_POST['news_mod_button']))
		{
			$sub_title = 'Zmień newsa';
			$result3 = $database->query("SELECT title, news_text FROM news WHERE id = ".$_POST['news_mod_num'].' ;');
			$row3 = $result3->fetch_assoc();
			$news_title = $row3['title'];
			$news_cont = $row3['news_text'];
		}
		if (!$ok_flag)
		{
			$_SESSION['news'] = 1;
			echo $sub_title;
			if (!empty($sub_title))	{	echo ":<br />\n";	}	?>
<form action="?category=admin/news" method="POST" >
<input type="hidden" name="news_num" value="<?php	echo $_SESSION['news_num'];	?>" />
<input type="hidden" name="news_mod_num" value="<?php
		if (isset($_POST['news_mod_num']))
			{	echo $_POST['news_mod_num'];	}
		else
			{	echo '0';	}
?>" />

Tytuł:<br />
<textarea id="task_title" name="news_titl" maxlength="255"><?php	echo $news_title;	?></textarea><br />
Treść:<br />
<textarea id="task_text" name="news_cont"><?php	echo $news_cont;	?></textarea><br />
<center><input type="submit" value="Wyślij" name="news_add_button" /></center>
</form><br/><hr/><br/><center>
Wybierz newsa do usunięcia lub edycji:
<br/><br/>
<?php
			$result2 = $database->query("SELECT id, title, clock FROM news ORDER BY clock DESC;");
			$numberRows = $result2->num_rows;
			$news = "";
			$row2 = $result2->fetch_assoc();
			$news = $news."<option value=\"".$row2['id']."\" selected=\"selected\">".$row2['title'].' ['.$row2['clock'].']'."</option>\n";
			for ($i=1; $i < $numberRows; $i++)
			{
				$row2 = $result2->fetch_assoc();
				$news = $news."<option value=\"".$row2['id']."\">".$row2['title'].' ['.$row2['clock'].']'."</option>\n";
			}
?>
<form action="?category=admin/news" method="POST" >
<input type="hidden" name="news_num" value="<?php	echo $_SESSION['news_num'];	?>" />

<select name="news_mod_num" id="news_edde">
<?php	echo $news;	?>
</select><br/><br/>
<input type="submit" value="Usuń" name="news_del_button" /> <input type="submit" value="Zmień" name="news_mod_button" /></center>
</form><br/><hr/><br/><center>
<a id="topper" href="index.php?category=admin/adminPanel">&#8593; Wróć do panelu &#8593;</a>
</center>
<?php
		}
		$database->close();
	}
} else {	echo "Nie masz uprawnien do przeglądania tej strony.<br />\n";	}?>
</p>

